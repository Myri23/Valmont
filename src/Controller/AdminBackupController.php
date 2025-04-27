<?php
// src/Controller/AdminBackupController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/admin')]
class AdminBackupController extends AbstractController
{
    private $backupDir;
    private $projectDir;
    
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->backupDir = $projectDir . '/var/backups';
    }
    
    #[Route('/sauvegardes', name: 'admin_backups')]
    public function index(): Response
    {
        // Vérifier les permissions
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Récupérer la liste des sauvegardes
        $backups = $this->getBackupsList();
        
        return $this->render('admin/backups.html.twig', [
            'backups' => $backups
        ]);
    }
    
    #[Route('/sauvegardes/nouvelle', name: 'admin_backup_create')]
    public function createBackup(Request $request): Response
    {
        // Vérifier les permissions
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Exécuter la commande de sauvegarde
        $process = new Process([
            'php',
            $this->projectDir . '/bin/console',
            'app:database:backup',
            '-vv' // Verbosité accrue pour voir les détails
        ]);
        
        // Augmenter le timeout à 30 minutes (1800 secondes)
        $process->setTimeout(1800);
        
        $process->run();
        
        if (!$process->isSuccessful()) {
            $errorOutput = $process->getErrorOutput();
            $output = $process->getOutput();
            $this->addFlash('error', 'Erreur lors de la sauvegarde: ' . ($errorOutput ?: $output));
        } else {
            $this->addFlash('success', 'Sauvegarde créée avec succès');
        }
        
        return $this->redirectToRoute('admin_backups');
    }
    
    #[Route('/sauvegardes/telecharger/{filename}', name: 'admin_backup_download')]
    public function downloadBackup(string $filename): Response
    {
        // Vérifier les permissions
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $filePath = $this->backupDir . '/' . $filename;
        
        // Vérifier que le fichier existe
        $filesystem = new Filesystem();
        if (!$filesystem->exists($filePath)) {
            throw $this->createNotFoundException('Sauvegarde non trouvée');
        }
        
        // Envoyer le fichier
        return $this->file($filePath);
    }
    
    #[Route('/sauvegardes/supprimer/{filename}', name: 'admin_backup_delete')]
    public function deleteBackup(string $filename): Response
    {
        // Vérifier les permissions
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $filePath = $this->backupDir . '/' . $filename;
        
        // Vérifier que le fichier existe
        $filesystem = new Filesystem();
        if ($filesystem->exists($filePath)) {
            $filesystem->remove($filePath);
            $this->addFlash('success', 'Sauvegarde supprimée avec succès');
        } else {
            $this->addFlash('error', 'Sauvegarde non trouvée');
        }
        
        return $this->redirectToRoute('admin_backups');
    }
    
    private function getBackupsList(): array
    {
        $filesystem = new Filesystem();
        if (!$filesystem->exists($this->backupDir)) {
            $filesystem->mkdir($this->backupDir);
            return [];
        }
        
        $backups = [];
        $files = glob($this->backupDir . '/*.sql');
        
        foreach ($files as $file) {
            $filename = basename($file);
            $backups[] = [
                'filename' => $filename,
                'size' => filesize($file),
                'date' => new \DateTime('@' . filemtime($file))
            ];
        }
        
        // Trier par date décroissante
        usort($backups, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });
        
        return $backups;
    }
}
