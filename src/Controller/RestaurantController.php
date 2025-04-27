<?php

namespace App\Controller;

use App\Entity\HistoriqueConsultation;
use App\Entity\Utilisateur;
use App\Service\HistoriqueConsultationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class RestaurantController extends AbstractController
{
    private $security;
    private $entityManager;
    private $historiqueService;

    public function __construct(
        Security $security, 
        EntityManagerInterface $entityManager,
        HistoriqueConsultationService $historiqueService
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->historiqueService = $historiqueService;
    }

    #[Route('/restaurants', name: 'restaurants')]
    public function index(): Response
    {
        return $this->render('information/restaurants.html.twig');
    }
    
#[Route('/restaurant/queen', name: 'restaurant_queen')]
public function queen(): Response
{
    // ID du restaurant Queen
    $restaurantId = 1;
    $restaurantName = 'Queen';
    
    // Enregistrer la consultation avec le nom
    $this->historiqueService->enregistrerConsultation('Restaurant', $restaurantId, $restaurantName);
    
    // Pointez vers le template correct
    return $this->render('information/queen.html.twig');
}

#[Route('/restaurant/belle-epoque', name: 'restaurant_belle_epoque')]
public function belleEpoque(): Response
{
    $restaurantId = 2;
    $restaurantName = 'La Belle Epoque';
    
    $this->historiqueService->enregistrerConsultation('Restaurant', $restaurantId, $restaurantName);
    
    // Pointez vers le template correct
    return $this->render('information/belle_epoque.html.twig');
}

#[Route('/restaurant/petite-sirene', name: 'restaurant_petite_sirene')]
public function petiteSirene(): Response
{
    $restaurantId = 3;
    $restaurantName = 'La Petite Sirène';
    
    $this->historiqueService->enregistrerConsultation('Restaurant', $restaurantId, $restaurantName);
    
    // Pointez vers le template correct
    return $this->render('information/petite_sirene.html.twig');
}
}
