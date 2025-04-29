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

/**
* Contrôleur pour la gestion des statistiques
* 
* Ce contrôleur regroupe toutes les actions liées aux statistiques
* des objets connectés, incluant la visualisation et l'export en PDF
*/
#[Route('/gestion/statistiques')]
class GestionStatistiqueController extends AbstractController
{
   /**
    * Affiche le tableau de bord principal des statistiques
    * 
    * @param ObjetConnecteRepository $objetConnecteRepository Repository des objets connectés
    * @return Response La réponse HTTP
    */
    #[Route('/', name: 'gestion_statistiques_index')]
    public function index(ObjetConnecteRepository $objetConnecteRepository): Response
    {
       /* Calcul des statistiques globales */
        $totalObjets = $objetConnecteRepository->count([]);
        $objetsActifs = $objetConnecteRepository->count(['actif' => true]);
        $objetsInactifs = $objetConnecteRepository->count(['actif' => false]);
        
       /* Calcul de la répartition par type d'objet */
        $repartitionTypes = $objetConnecteRepository->countByType();
        
       /* Calcul du nombre d'objets avec batterie faible */
        $batteriesFaibles = $objetConnecteRepository->count(['batteriePct' => '<25']);
        
       /* Affichage du template avec toutes les statistiques calculées */        
        return $this->render('gestion/statistiques/index.html.twig', [
            'total_objets' => $totalObjets,
            'objets_actifs' => $objetsActifs,
            'objets_inactifs' => $objetsInactifs,
            'repartition_types' => $repartitionTypes,
            'batteries_faibles' => $batteriesFaibles,
        ]);
    }

   /**
    * Génère un rapport PDF pour un type spécifique d'objet connecté
    * 
    * @param string $type Type d'objet connecté pour le rapport
    * @param ObjetConnecteRepository $objetConnecteRepository Repository des objets connectés
    * @return Response La réponse HTTP contenant le fichier PDF
    */
    #[Route('/rapport/{type}', name: 'gestion_statistiques_rapport')]
    public function genererRapport(string $type, ObjetConnecteRepository $objetConnecteRepository): Response
    {
       /* Récupération des objets du type spécifié */
        $objets = $objetConnecteRepository->findBy(['type' => $type]);
        
       /* Génération du contenu HTML du rapport */
        $html = $this->renderView('gestion/statistiques/rapport.html.twig', [
            'objets' => $objets,
            'type' => $type,
            'date' => new \DateTime(),
        ]);
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
       /* Initialisation de Dompdf avec les options */
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
       /* 
        * Création de la réponse HTTP avec le contenu PDF généré
        * et configuration des en-têtes pour le téléchargement
        */
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport_'.$type.'_'.date('Y-m-d').'.pdf"');
        
        return $response;
    }

   /**
    * Affiche l'analyse d'efficacité des objets connectés
    * 
    * @param ObjetConnecteRepository $objetConnecteRepository Repository des objets connectés
    * @return Response La réponse HTTP
    */
    #[Route('/efficacite', name: 'gestion_statistiques_efficacite')]
    public function analyseEfficacite(ObjetConnecteRepository $objetConnecteRepository): Response
    {

        $objetsBatterieFaible = $objetConnecteRepository->findBy(['batteriePct' => '<15']);
        
       /* 
        * Identification des objets inactifs
        * (pas d'interaction depuis plus de 30 jours)
        */
        $date = new \DateTime();
        $date->modify('-30 days');
        $objetsInactifs = $objetConnecteRepository->createQueryBuilder('o')
            ->where('o.derniereInteraction < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();

       /* Affichage du template avec les objets nécessitant attention */
        return $this->render('gestion/statistiques/efficacite.html.twig', [
            'objets_batterie_faible' => $objetsBatterieFaible,
            'objets_inactifs' => $objetsInactifs,
        ]);
    }
}
