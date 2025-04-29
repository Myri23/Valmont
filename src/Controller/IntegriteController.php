<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\IntegriteService;

/**
* Contrôleur pour la vérification et la réparation de l'intégrité des données
* 
* Ce contrôleur permet aux administrateurs de vérifier l'intégrité
* des données et de lancer des procédures de réparation si nécessaire
*/
class IntegriteController extends AbstractController
{
   /**
    * Affiche la page de vérification de l'intégrité des données
    * 
    * @param IntegriteService $integriteService Service d'intégrité des données
    * @return Response La réponse HTTP
    */
    #[Route('/admin/integrite', name: 'admin_integrite_donnees')]
    function index(IntegriteService $integriteService): Response
    {
       /* Exécution des vérifications d'intégrité */    
        $resultats = $integriteService->verifierIntegrite();
        
       /* Affichage des résultats dans le template */        
        return $this->render('admin/integrite/index.html.twig', [
            'resultats' => $resultats,
        ]);
    }

   /**
    * Lance la procédure de réparation pour un type spécifique de problème
    * 
    * @param string $type Type de problème à réparer
    * @param IntegriteService $integriteService Service d'intégrité des données
    * @return Response La réponse HTTP
    */
    #[Route('/admin/integrite/reparer/{type}', name: 'admin_integrite_reparer')]
    public function reparer(string $type, IntegriteService $integriteService): Response
    {
       /* Exécution de la réparation pour le type spécifié */    
        $integriteService->reparer($type);
        
       /* Message de confirmation pour l'utilisateur */        
        $this->addFlash('success', 'Réparation effectuée avec succès');
        
        return $this->redirectToRoute('admin_integrite_donnees');
    }
}
