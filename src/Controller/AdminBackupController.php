<?php
// src/Controller/AdminBackupController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;

/**
* Contrôleur pour la gestion des sauvegardes
* 
* Ce contrôleur regroupe les fonctionnalités de création,
* téléchargement et suppression des sauvegardes de la base de données
*/
#[Route('/admin')]
class AdminBackupController extends AbstractController
{
    private $backupDir;
    private $projectDir;

   /**
    * Constructeur du contrôleur avec injection du chemin du projet
    * 
    * @param string $projectDir Chemin du répertoire racine du projet
    */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->backupDir = $projectDir . '/var/backups';
    }

   /**
    * Affiche la liste des sauvegardes disponibles
    * 
    * Cette méthode récupère toutes les sauvegardes existantes
    * et les affiche dans l'interface d'administration
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/sauvegardes', name: 'admin_backups')]
    public function index(): Response
    {
       /* Vérifier que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       /* Récupérer la liste des sauvegardes disponibles */
        $backups = $this->getBackupsList();

       /* Affichage du template avec la liste des sauvegardes */
        return $this->render('admin/backups.html.twig', [
            'backups' => $backups
        ]);
    }

   /**
    * Crée une nouvelle sauvegarde de la base de données
    * 
    * @param Request $request Requête HTTP
    * @return Response La réponse HTTP
    */
    #[Route('/sauvegardes/nouvelle', name: 'admin_backup_create')]
    public function createBackup(Request $request): Response
    {
       /* Vérifier que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       /* 
        * Exécuter la commande de sauvegarde via la console Symfony
        * en utilisant une commande personnalisée
        */
        $process = new Process([
            'php',
            $this->projectDir . '/bin/console',
            'app:database:backup',
            '-vv' 
        ]);
        
       /* Augmenter le timeout à 30 minutes (1800 secondes) pour les grandes bases */
        $process->setTimeout(1800);
        
        $process->run();

       /* Vérifier si la commande s'est exécutée avec succès */
        if (!$process->isSuccessful()) {
            $errorOutput = $process->getErrorOutput();
            $output = $process->getOutput();
            $this->addFlash('error', 'Erreur lors de la sauvegarde: ' . ($errorOutput ?: $output));
        } else {
            $this->addFlash('success', 'Sauvegarde créée avec succès');
        }
 
        /* Redirection vers la liste des sauvegardes */
        return $this->redirectToRoute('admin_backups');
    }

   /**
    * Permet le téléchargement d'une sauvegarde
    * 
    * Cette méthode envoie le fichier de sauvegarde au navigateur
    * pour permettre à l'administrateur de le télécharger
    * 
    * @param string $filename Nom du fichier de sauvegarde
    * @return Response La réponse HTTP contenant le fichier
    */
    #[Route('/sauvegardes/telecharger/{filename}', name: 'admin_backup_download')]
    public function downloadBackup(string $filename): Response
    {
       /* Vérifier que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $filePath = $this->backupDir . '/' . $filename;
        
       /* Vérifier que le fichier existe */
        $filesystem = new Filesystem();
        if (!$filesystem->exists($filePath)) {
            throw $this->createNotFoundException('Sauvegarde non trouvée');
        }
        
       /* Envoyer le fichier en tant que pièce jointe à télécharger */
        return $this->file($filePath);
    }

   /**
    * Supprime une sauvegarde existante
    * 
    * Cette méthode supprime un fichier de sauvegarde
    * du système de fichiers
    * 
    * @param string $filename Nom du fichier de sauvegarde à supprimer
    * @return Response La réponse HTTP
    */
    #[Route('/sauvegardes/supprimer/{filename}', name: 'admin_backup_delete')]
    public function deleteBackup(string $filename): Response
    {
       /* Vérifier que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $filePath = $this->backupDir . '/' . $filename;
        
       /* Vérifier que le fichier existe avant de le supprimer */
        $filesystem = new Filesystem();
        if ($filesystem->exists($filePath)) {
           /* Suppression du fichier */        
            $filesystem->remove($filePath);
           /* Message de confirmation */            
            $this->addFlash('success', 'Sauvegarde supprimée avec succès');
        } else {
           /* Message d'erreur si le fichier n'existe pas */        
            $this->addFlash('error', 'Sauvegarde non trouvée');
        }
       /* Redirection vers la liste des sauvegardes */        
        return $this->redirectToRoute('admin_backups');
    }


   /**
    * Récupère la liste des sauvegardes disponibles
    * 
    * @return array Liste des sauvegardes avec leurs métadonnées
    */
    private function getBackupsList(): array
    {
       /* Initialisation du système de fichiers */    
        $filesystem = new Filesystem();
       /* Créer le répertoire de sauvegarde s'il n'existe pas */        
        if (!$filesystem->exists($this->backupDir)) {
            $filesystem->mkdir($this->backupDir);
            return [];
        }

       /* Recherche de tous les fichiers SQL dans le répertoire */
        $backups = [];
        $files = glob($this->backupDir . '/*.sql');

       /* Parcours des fichiers pour récupérer leurs métadonnées */
        foreach ($files as $file) {
            $filename = basename($file);
            $backups[] = [
                'filename' => $filename,
                'size' => filesize($file),
                'date' => new \DateTime('@' . filemtime($file))
            ];
        }
        
       /* Tri des sauvegardes par date décroissante (plus récentes d'abord) */
        usort($backups, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });
        
        return $backups;
    }
}
