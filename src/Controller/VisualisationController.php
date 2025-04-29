<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\PoubelleConnectee;
use App\Form\ObjetConnecteType;
use App\Form\PoubelleConnecteeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur pour la visualisation des objets connectés.
*/
final class VisualisationController extends AbstractController
{
    /**
     * Affiche tous les objets connectés.
     *
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @return Response Réponse HTTP contenant la vue des objets connectés
     */
    #[Route('/visualisation', name: 'visualisation')]
    public function visualisation(EntityManagerInterface $entityManager): Response
    {
        // Récupération de tous les objets connectés depuis la base de données    
        $objets = $entityManager->getRepository(ObjetConnecte::class)->findAll();
        
        // Rendu du template avec la liste des objets connectés
        return $this->render('visualisation/index.html.twig', [
            'controller_name' => 'VisualisationController',
            'objets' => $objets,
        ]);
    }
}
