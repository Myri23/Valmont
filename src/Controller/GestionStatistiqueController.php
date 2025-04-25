<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Repository\ObjetConnecteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/gestion/statistiques')]
class GestionStatistiquesController extends AbstractController
{
    #[Route('/', name: 'gestion_statistiques_index')]
    public function index(ObjetConnecteRepository $objetConnecteRepository): Response
    {
        // Statistiques globales
        $totalObjets = $objetConnecteRepository->count([]);
        $objetsActifs = $objetConnecteRepository->count(['actif' => true]);
        $objetsInactifs = $objetConnecteRepository->count(['actif' => false]);
        
        // Répartition par type
        $repartitionTypes = $objetConnecteRepository->countByType();
        
        // Répartition par état de batterie
        $batteriesFaibles = $objetConnecteRepository->count(['batteriePct' => '<25']);
        
        return $this->render('gestion/statistiques/index.html.twig', [
            'total_objets' => $totalObjets,
            'objets_actifs' => $objetsActifs,
            'objets_inactifs' => $objetsInactifs,
            'repartition_types' => $repartitionTypes,
            'batteries_faibles' => $batteriesFaibles,
        ]);
    }
    
    #[Route('/rapport/{type}', name: 'gestion_statistiques_rapport')]
    public function genererRapport(string $type, ObjetConnecteRepository $objetConnecteRepository): Response
    {
        // Récupérer les données pour le rapport
        $objets = $objetConnecteRepository->findBy(['type' => $type]);
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('gestion/statistiques/rapport.html.twig', [
            'objets' => $objets,
            'type' => $type,
            'date' => new \DateTime(),
        ]);
        
        // Configurer DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        // Instancier Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Générer le PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport_'.$type.'_'.date('Y-m-d').'.pdf"');
        
        return $response;
    }
    
    #[Route('/efficacite', name: 'gestion_statistiques_efficacite')]
    public function analyseEfficacite(ObjetConnecteRepository $objetConnecteRepository): Response
    {
        // Objets nécessitant une maintenance (batterie faible)
        $objetsBatterieFaible = $objetConnecteRepository->findBy(['batteriePct' => '<15']);
        
        // Objets avec peu d'interactions récentes
        $date = new \DateTime();
        $date->modify('-30 days');
        $objetsInactifs = $objetConnecteRepository->createQueryBuilder('o')
            ->where('o.derniereInteraction < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
        
        return $this->render('gestion/statistiques/efficacite.html.twig', [
            'objets_batterie_faible' => $objetsBatterieFaible,
            'objets_inactifs' => $objetsInactifs,
        ]);
    }
}