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

final class VisualisationController extends AbstractController
{
    #[Route('/visualisation', name: 'visualisation')]
    public function visualisation(EntityManagerInterface $entityManager): Response
    {
        $objets = $entityManager->getRepository(ObjetConnecte::class)->findAll();

        return $this->render('visualisation/index.html.twig', [
            'controller_name' => 'VisualisationController',
            'objets' => $objets,
        ]);
    }
}
