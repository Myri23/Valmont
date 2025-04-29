<?php

namespace App\Controller;

use App\Repository\HistoriqueConsultationRepository;
use App\Repository\HistoriqueConnexionRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

/**
* Contrôleur pour l'exportation des données
* 
* Ce contrôleur permet aux administrateurs d'exporter des données
* du système au format CSV pour analyse externe
*/
class ExportController extends AbstractController
{
    private $entityManager;
    private $utilisateurRepository;
    private $historiqueConnexionRepository;
    private $historiqueConsultationRepository;

   /**
    * Constructeur du contrôleur avec injection des dépendances
    * 
    * @param EntityManagerInterface $entityManager Gestionnaire d'entités
    * @param UtilisateurRepository $utilisateurRepository Repository des utilisateurs
    * @param HistoriqueConnexionRepository $historiqueConnexionRepository Repository des connexions
    * @param HistoriqueConsultationRepository $historiqueConsultationRepository Repository des consultations
    */
    public function __construct(
        EntityManagerInterface $entityManager,
        UtilisateurRepository $utilisateurRepository,
        HistoriqueConnexionRepository $historiqueConnexionRepository,
        HistoriqueConsultationRepository $historiqueConsultationRepository
    ) {
       /* Initialisation des repositories et du gestionnaire d'entités */    
        $this->entityManager = $entityManager;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->historiqueConnexionRepository = $historiqueConnexionRepository;
        $this->historiqueConsultationRepository = $historiqueConsultationRepository;
    }

   /**
    * Affiche la page d'index des exports
    * 
    * Cette méthode affiche la page avec les statistiques de base
    * et les options d'exportation disponibles
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/export', name: 'export_index')]
    public function index(): Response
    {
       /* Vérification que l'utilisateur est administrateur */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       /* Préparation des statistiques de base pour la page */
        $stats = [
            'total_utilisateurs' => $this->utilisateurRepository->count([]),
            'total_connexions' => $this->historiqueConnexionRepository->count([]),
            'total_consultations' => $this->historiqueConsultationRepository->count([]),
        ];
       /* Affichage du template avec les statistiques */
        return $this->render('admin/export_index.html.twig', [
            'stats' => $stats,
        ]);
    }

   /**
    * Génère et télécharge le fichier d'export CSV
    * 
    * @param Request $request La requête HTTP
    * @return Response La réponse HTTP avec le fichier CSV
    */
    #[Route('/export/generer', name: 'export_generer')]
    public function generer(Request $request): Response
    {
       /* Vérification que l'utilisateur est administrateur */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $format = $request->query->get('format', 'csv');
        $type = $request->query->get('type', 'connexions');
        
       /* Récupération des données selon le type de rapport demandé */
        switch ($type) {
            case 'connexions':
                $data = $this->getConnexionsData();
                $headers = ['ID', 'Utilisateur', 'Date de connexion', 'IP'];
                $filename = 'connexions_' . date('Y-m-d');
                break;
            
            case 'consultations':
                $data = $this->getConsultationsData();
                $headers = ['ID', 'Utilisateur', 'Type d\'élément', 'Élément consulté', 'Date de consultation'];
                $filename = 'consultations_' . date('Y-m-d');
                break;
            
            case 'utilisateurs':
                $data = $this->getUtilisateursData();
                $headers = ['ID', 'Login', 'Nom', 'Prénom', 'Email', 'Type', 'Niveau', 'Points connexion', 'Points consultation'];
                $filename = 'utilisateurs_' . date('Y-m-d');
                break;
            
            default:
                throw $this->createNotFoundException('Type de rapport inconnu.');
        }

       /* Génération du contenu CSV en commençant par les en-têtes */
        $csv = implode(',', $headers) . "\n";

       /* Ajout des lignes de données */
        foreach ($data as $row) {
            $csv .= implode(',', array_map(function($cell) {
                // Échapper les virgules et les guillemets
                if (is_string($cell) && (strpos($cell, ',') !== false || strpos($cell, '"') !== false)) {
                    return '"' . str_replace('"', '""', $cell) . '"';
                }
                return $cell;
            }, $row)) . "\n";
        }
        
        $response = new Response($csv);
        
       /* Configuration des en-têtes pour le téléchargement du fichier */        
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename . '.csv'
        );
        
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        
        return $response;
    }

   /**
    * Récupère les données d'historique de connexions
    * 
    * @return array Tableau de données formatées
    */
    private function getConnexionsData(): array
    {
       /* Création de la requête pour récupérer les données */    
        $qb = $this->historiqueConnexionRepository->createQueryBuilder('c')
            ->select('c.id', 'u.login', 'c.dateConnexion', 'c.ipConnexion')
            ->leftJoin('c.utilisateur', 'u')
            ->orderBy('c.dateConnexion', 'DESC')
            ->setMaxResults(1000); // Limiter à 1000 connexions pour des raisons de performance
        
        $result = $qb->getQuery()->getArrayResult();
        
       /* Formatage des données pour l'export CSV */
        $data = [];
        foreach ($result as $row) {
            $data[] = [
                $row['id'],
                $row['login'],
                $row['dateConnexion']->format('d/m/Y H:i:s'),
                $row['ipConnexion'] ?? 'N/A'
            ];
        }
        
        return $data;
    }


   /**
    * Récupère les données d'historique de consultations
    * 
    * Cette méthode privée récupère et formate les données
    * d'historique de consultations pour l'export
    * 
    * @return array Tableau de données formatées
    */
    private function getConsultationsData(): array
    {
        $qb = $this->historiqueConsultationRepository->createQueryBuilder('c')
            ->select('c.id', 'u.login', 'c.typeElement', 'c.nomElement', 'c.dateConsultation')
            ->leftJoin('c.utilisateur', 'u')
            ->orderBy('c.dateConsultation', 'DESC')
            ->setMaxResults(1000); // Limiter à 1000 consultations pour des raisons de performance
        
        $result = $qb->getQuery()->getArrayResult();
        
       /* Formatage des données pour l'export CSV */
        $data = [];
        foreach ($result as $row) {
            $data[] = [
                $row['id'],
                $row['login'],
                $row['typeElement'] ?? 'N/A',
                $row['nomElement'] ?? 'N/A',
                $row['dateConsultation']->format('d/m/Y H:i:s')
            ];
        }
        
        return $data;
    }

   /**
    * Récupère les données des utilisateurs
    * 
    * Cette méthode privée récupère et formate les données
    * des utilisateurs pour l'export
    * 
    * @return array Tableau de données formatées
    */
    private function getUtilisateursData(): array
    {
        $qb = $this->utilisateurRepository->createQueryBuilder('u')
            ->select('u.id', 'u.login', 'u.nom', 'u.prenom', 'u.email', 'u.type_utilisateur', 
                     'u.niveau_experience', 'u.points_connexion', 'u.pointsConsultation')
            ->orderBy('u.id', 'ASC');
       /* Exécution de la requête */    
        $result = $qb->getQuery()->getArrayResult();
    
       /* Formatage des données pour l'export CSV */
       $data = [];
       foreach ($result as $row) {
           $data[] = [
               $row['id'],
               $row['login'],
               $row['nom'],
               $row['prenom'],
               $row['email'],
               $row['type_utilisateur'],
               $row['niveau_experience'],
               $row['points_connexion'],
               $row['pointsConsultation']
            ];
        }
    
        return $data;
    }
}
