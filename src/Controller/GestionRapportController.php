<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\PoubelleConnectee;
use App\Entity\ParkingIntelligent;
use App\Entity\LampadaireIntelligent;
use App\Repository\ObjetConnecteRepository;
use App\Repository\PoubelleConnecteeRepository;
use App\Repository\ParkingIntelligentRepository;
use App\Repository\LampadaireIntelligentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/gestion/rapports')]
class GestionRapportController extends AbstractController
{
    #[Route('/objets', name: 'app_gestion_rapport_objets')]
    public function genererRapportObjets(ObjetConnecteRepository $objetRepository): Response
    {
        $objets = $objetRepository->findAll();
        
        // Calculer quelques statistiques
        $objetActifs = 0;
        $objetInactifs = 0;
        $batterieFaible = 0;
        
        foreach ($objets as $objet) {
            if ($objet->isActif()) {
                $objetActifs++;
            } else {
                $objetInactifs++;
            }
            
            if ($objet->getBatteriePct() < 25) {
                $batterieFaible++;
            }
        }
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('gestion/rapports/objets.html.twig', [
            'objets' => $objets,
            'objetActifs' => $objetActifs,
            'objetInactifs' => $objetInactifs,
            'batterieFaible' => $batterieFaible,
            'date' => new \DateTime(),
            'titre' => 'Rapport des Objets Connectés'
        ]);
        
        return $this->generatePdf($html, 'rapport_objets');
    }
    
    #[Route('/poubelles', name: 'app_gestion_rapport_poubelles')]
    public function genererRapportPoubelles(PoubelleConnecteeRepository $poubelleRepository): Response
    {
        $poubelles = $poubelleRepository->findAll();
        
        // Calculer le niveau de remplissage moyen
        $niveauRemplissageTotal = 0;
        $poubellesPresquePleines = 0;
        
        foreach ($poubelles as $poubelle) {
            $niveauRemplissageTotal += $poubelle->getNiveauRemplissage();
            
            if ($poubelle->getNiveauRemplissage() > 80) {
                $poubellesPresquePleines++;
            }
        }
        
        $niveauRemplissageMoyen = count($poubelles) > 0 
            ? $niveauRemplissageTotal / count($poubelles) 
            : 0;
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('gestion/rapports/poubelles.html.twig', [
            'poubelles' => $poubelles,
            'niveauRemplissageMoyen' => round($niveauRemplissageMoyen, 1),
            'poubellesPresquePleines' => $poubellesPresquePleines,
            'date' => new \DateTime(),
            'titre' => 'Rapport des Poubelles Connectées'
        ]);
        
        return $this->generatePdf($html, 'rapport_poubelles');
    }
    
    #[Route('/parkings', name: 'app_gestion_rapport_parkings')]
    public function genererRapportParkings(ParkingIntelligentRepository $parkingRepository): Response
    {
        $parkings = $parkingRepository->findAll();
        
        // Calculer des statistiques si nécessaire
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('gestion/rapports/parkings.html.twig', [
            'parkings' => $parkings,
            'date' => new \DateTime(),
            'titre' => 'Rapport des Parkings Intelligents'
        ]);
        
        return $this->generatePdf($html, 'rapport_parkings');
    }
    
    #[Route('/lampadaires', name: 'app_gestion_rapport_lampadaires')]
    public function genererRapportLampadaires(LampadaireIntelligentRepository $lampadaireRepository): Response
    {
        $lampadaires = $lampadaireRepository->findAll();
        
        // Calculer des statistiques si nécessaire
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('gestion/rapports/lampadaires.html.twig', [
            'lampadaires' => $lampadaires,
            'date' => new \DateTime(),
            'titre' => 'Rapport des Lampadaires Intelligents'
        ]);
        
        return $this->generatePdf($html, 'rapport_lampadaires');
    }
    
    /**
     * Méthode utilitaire pour générer un PDF
     */
    private function generatePdf(string $html, string $filename): Response
    {
        // Configurer DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        
        // Instancier Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Générer le PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'_'.date('Y-m-d').'.pdf"');
        
        return $response;
    }
}
