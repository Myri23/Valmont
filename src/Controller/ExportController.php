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

class ExportController extends AbstractController
{
    private $entityManager;
    private $utilisateurRepository;
    private $historiqueConnexionRepository;
    private $historiqueConsultationRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UtilisateurRepository $utilisateurRepository,
        HistoriqueConnexionRepository $historiqueConnexionRepository,
        HistoriqueConsultationRepository $historiqueConsultationRepository
    ) {
        $this->entityManager = $entityManager;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->historiqueConnexionRepository = $historiqueConnexionRepository;
        $this->historiqueConsultationRepository = $historiqueConsultationRepository;
    }

    #[Route('/export', name: 'export_index')]
    public function index(): Response
    {
        // Vérification que l'utilisateur est admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Données de base pour la page
        $stats = [
            'total_utilisateurs' => $this->utilisateurRepository->count([]),
            'total_connexions' => $this->historiqueConnexionRepository->count([]),
            'total_consultations' => $this->historiqueConsultationRepository->count([]),
        ];

        return $this->render('admin/export_index.html.twig', [
            'stats' => $stats,
        ]);
    }

    #[Route('/export/generer', name: 'export_generer')]
    public function generer(Request $request): Response
    {
        // Vérification que l'utilisateur est admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $format = $request->query->get('format', 'csv');
        $type = $request->query->get('type', 'connexions');
        
        // Récupérer les données selon le type de rapport
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

        // Exporter les données au format CSV (le plus simple)
        $csv = implode(',', $headers) . "\n";
        
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
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename . '.csv'
        );
        
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        
        return $response;
    }

    private function getConnexionsData(): array
    {
        $qb = $this->historiqueConnexionRepository->createQueryBuilder('c')
            ->select('c.id', 'u.login', 'c.dateConnexion', 'c.ipConnexion')
            ->leftJoin('c.utilisateur', 'u')
            ->orderBy('c.dateConnexion', 'DESC')
            ->setMaxResults(1000); // Limiter à 1000 connexions pour des raisons de performance
        
        $result = $qb->getQuery()->getArrayResult();
        
        // Formatage des données
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

    private function getConsultationsData(): array
    {
        $qb = $this->historiqueConsultationRepository->createQueryBuilder('c')
            ->select('c.id', 'u.login', 'c.typeElement', 'c.nomElement', 'c.dateConsultation')
            ->leftJoin('c.utilisateur', 'u')
            ->orderBy('c.dateConsultation', 'DESC')
            ->setMaxResults(1000); // Limiter à 1000 consultations pour des raisons de performance
        
        $result = $qb->getQuery()->getArrayResult();
        
        // Formatage des données
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

private function getUtilisateursData(): array
{
    $qb = $this->utilisateurRepository->createQueryBuilder('u')
        ->select('u.id', 'u.login', 'u.nom', 'u.prenom', 'u.email', 'u.type_utilisateur', 
                 'u.niveau_experience', 'u.points_connexion', 'u.pointsConsultation')
        ->orderBy('u.id', 'ASC');
    
    $result = $qb->getQuery()->getArrayResult();
    
    // Formatage des données
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
