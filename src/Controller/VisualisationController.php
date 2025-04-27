<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VisualisationController extends AbstractController
{
    #[Route('/visualisation', name: 'app_visualisation')]
    public function index(): Response
    {
        return $this->render('visualisation/index.html.twig', [
            'controller_name' => 'VisualisationController',
        ]);
    }
}
