<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ManagementController extends AbstractController
{
    /**
    * Contrôleur pour les pages d'administration et de gestion
    * 
    * Ce contrôleur est marqué comme 'final' pour empêcher l'extension
    * et gère les pages de l'interface d'administration
    */
    #[Route('/management', name: 'app_management')]
    public function index(): Response
    {
       /* 
        * Affichage du template pour la page d'administration
        * avec le nom du contrôleur passé comme variable
        */    
        return $this->render('management/index.html.twig', [
            'controller_name' => 'ManagementController',
        ]);
    }
}
