<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VisualizationController extends AbstractController
{
    #[Route('/visualization', name: 'app_visualization')]
    public function index(): Response
    {
        return $this->render('visualization/index.html.twig', [
            'controller_name' => 'VisualizationController',
        ]);
    }
}
