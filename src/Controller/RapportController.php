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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/rapports')]
class RapportController extends AbstractController
{
    /**
     * Génère un rapport PDF pour tous les objets connectés
     */
    #[Route('/objets-connectes', name: 'app_rapport_objets_connectes')]
    public function objetsConnectes(ObjetConnecteRepository $objetRepository): Response
    {
        $objets = $objetRepository->findAll();
        
        // Compter les objets par type
        $countByType = [];
        foreach ($objets as $objet) {
            $type = $objet->getType();
            if (!isset($countByType[$type])) {
                $countByType[$type] = 0;
            }
            $countByType[$type]++;
        }
        
        // Compter les objets par état
        $countByEtat = [];
        foreach ($objets as $objet) {
            $etat = $objet->getEtat();
            if (!isset($countByEtat[$etat])) {
                $countByEtat[$etat] = 0;
            }
            $countByEtat[$etat]++;
        }
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('rapports/objets_connectes.html.twig', [
            'objets' => $objets,
            'countByType' => $countByType,
            'countByEtat' => $countByEtat,
            'date' => new \DateTime(),
        ]);
        
        return $this->generatePdf($html, 'Objets Connectés');
    }
    
    /**
     * Génère un rapport PDF pour les poubelles connectées
     */
    #[Route('/poubelles-connectees', name: 'app_rapport_poubelles')]
    public function poubellesConnectees(PoubelleConnecteeRepository $poubelleRepository): Response
    {
        $poubelles = $poubelleRepository->findAll();
        
        // Calculer le niveau de remplissage moyen
        $niveauRemplissageTotal = 0;
        foreach ($poubelles as $poubelle) {
            $niveauRemplissageTotal += $poubelle->getNiveauRemplissage();
        }
        $niveauRemplissageMoyen = count($poubelles) > 0 ? $niveauRemplissageTotal / count($poubelles) : 0;
        
        // Compter les poubelles par type de déchets
        $countByTypeDechet = [];
        foreach ($poubelles as $poubelle) {
            $typeDechet = $poubelle->getTypeDechet();
            if (!isset($countByTypeDechet[$typeDechet])) {
                $countByTypeDechet[$typeDechet] = 0;
            }
            $countByTypeDechet[$typeDechet]++;
        }
        
        // Nombre de poubelles presque pleines
        $nombrePresquePleine = 0;
        foreach ($poubelles as $poubelle) {
            if ($poubelle->getNiveauRemplissage() > 80) {
                $nombrePresquePleine++;
            }
        }
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('rapports/poubelles.html.twig', [
            'poubelles' => $poubelles,
            'niveauRemplissageMoyen' => round($niveauRemplissageMoyen, 2),
            'countByTypeDechet' => $countByTypeDechet,
            'nombrePresquePleine' => $nombrePresquePleine,
            'date' => new \DateTime(),
        ]);
        
        return $this->generatePdf($html, 'Poubelles Connectées');
    }
    
    /**
     * Génère un rapport PDF pour les parkings intelligents
     */
    #[Route('/parkings-intelligents', name: 'app_rapport_parkings')]
    public function parkingsIntelligents(ParkingIntelligentRepository $parkingRepository): Response
    {
        $parkings = $parkingRepository->findAll();
        
        // Calculer le nombre total de places
        $totalPlaces = 0;
        $placesDisponibles = 0;
        foreach ($parkings as $parking) {
            $totalPlaces += $parking->getPlacesTotales();
            $placesDisponibles += $parking->getPlacesDisponibles();
        }
        
        // Taux d'occupation moyen
        $tauxOccupation = $totalPlaces > 0 ? (($totalPlaces - $placesDisponibles) / $totalPlaces) * 100 : 0;
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('rapports/parkings.html.twig', [
            'parkings' => $parkings,
            'totalPlaces' => $totalPlaces,
            'placesDisponibles' => $placesDisponibles,
            'tauxOccupation' => round($tauxOccupation, 2),
            'date' => new \DateTime(),
        ]);
        
        return $this->generatePdf($html, 'Parkings Intelligents');
    }
    
    /**
     * Génère un rapport PDF pour les lampadaires intelligents
     */
    #[Route('/lampadaires-intelligents', name: 'app_rapport_lampadaires')]
    public function lampadairesIntelligents(LampadaireIntelligentRepository $lampadaireRepository): Response
    {
        $lampadaires = $lampadaireRepository->findAll();
        
        // Compter les lampadaires par état
        $countByEtat = [];
        foreach ($lampadaires as $lampadaire) {
            $etat = $lampadaire->getObjet()->getEtat();
            if (!isset($countByEtat[$etat])) {
                $countByEtat[$etat] = 0;
            }
            $countByEtat[$etat]++;
        }
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('rapports/lampadaires.html.twig', [
            'lampadaires' => $lampadaires,
            'countByEtat' => $countByEtat,
            'date' => new \DateTime(),
        ]);
        
        return $this->generatePdf($html, 'Lampadaires Intelligents');
    }
    
    /**
     * Génère un rapport statistique global
     */
    #[Route('/statistiques', name: 'app_rapport_statistiques')]
    public function statistiques(
        ObjetConnecteRepository $objetRepository,
        PoubelleConnecteeRepository $poubelleRepository,
        ParkingIntelligentRepository $parkingRepository,
        LampadaireIntelligentRepository $lampadaireRepository
    ): Response {
        // Statistiques générales
        $nombreObjets = $objetRepository->count([]);
        $nombrePoubelles = $poubelleRepository->count([]);
        $nombreParkings = $parkingRepository->count([]);
        $nombreLampadaires = $lampadaireRepository->count([]);
        
        // Statistiques d'état
        $objetsBatterieFaible = $objetRepository->count(['batteriePct' => '<25']);
        $objetsMaintenance = $objetRepository->count(['etat' => 'Maintenance']);
        $objetsActifs = $objetRepository->count(['actif' => true]);
        
        // Générer le contenu HTML du rapport
        $html = $this->renderView('rapports/statistiques.html.twig', [
            'nombreObjets' => $nombreObjets,
            'nombrePoubelles' => $nombrePoubelles,
            'nombreParkings' => $nombreParkings,
            'nombreLampadaires' => $nombreLampadaires,
            'objetsBatterieFaible' => $objetsBatterieFaible,
            'objetsMaintenance' => $objetsMaintenance,
            'objetsActifs' => $objetsActifs,
            'date' => new \DateTime(),
        ]);
        
        return $this->generatePdf($html, 'Statistiques Globales');
    }
    
    /**
     * Méthode utilitaire pour générer un PDF
     */
    private function generatePdf(string $html, string $title): Response
    {
        // Configurer DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        // Instancier Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Générer le PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport_' . $this->slugify($title) . '_' . date('Y-m-d') . '.pdf"');
        
        return $response;
    }
    
    /**
     * Convertit une chaîne en slug
     */
    private function slugify(string $text): string
    {
        // Remplacer les caractères non alphanumériques par des tirets
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // Translittération
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // Supprimer les caractères indésirables
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Remplacer les tirets multiples par un seul
        $text = preg_replace('~-+~', '-', $text);
        // Trimmer les tirets au début et à la fin
        $text = trim($text, '-');
        // Convertir en minuscules
        $text = strtolower($text);
        
        return $text;
    }
}
