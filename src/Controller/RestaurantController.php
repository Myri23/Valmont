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

/**
 * Contrôleur gérant l'affichage des restaurants.
 */
class RestaurantController extends AbstractController
{
    private $security;
    private $entityManager;
    private $historiqueService;

    /**
     * Constructeur avec injection des dépendances.
     * 
     * @param Security $security Service de sécurité
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @param HistoriqueConsultationService $historiqueService Service d'historique des consultations
     */
    public function __construct(
        Security $security, 
        EntityManagerInterface $entityManager,
        HistoriqueConsultationService $historiqueService
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->historiqueService = $historiqueService;
    }

    /**
     * Affiche la page principale des restaurants.
     * 
     * @return Response Réponse HTTP contenant la page des restaurants
     */
    #[Route('/restaurants', name: 'restaurants')]
    public function index(): Response
    {
        return $this->render('information/restaurants.html.twig');
    }
    
    /**
     * Affiche les informations du restaurant Queen.
     * Enregistre la consultation dans l'historique.
     * 
     * @return Response Réponse HTTP contenant la page du restaurant Queen
     */    
     #[Route('/restaurant/queen', name: 'restaurant_queen')]
     public function queen(): Response
    {
        $restaurantId = 1;
        $restaurantName = 'Queen';
    
        // Enregistrer la consultation avec le nom
        $this->historiqueService->enregistrerConsultation('Restaurant', $restaurantId, $restaurantName);
    
        // Pointez vers le template correct
        return $this->render('information/queen.html.twig');
    }

    /**
     * Affiche les informations du restaurant La Belle Epoque.
     * Enregistre la consultation dans l'historique.
     * 
     * @return Response Réponse HTTP contenant la page du restaurant La Belle Epoque
     */
    #[Route('/restaurant/belle-epoque', name: 'restaurant_belle_epoque')]
    public function belleEpoque(): Response
    {
        $restaurantId = 2;
        $restaurantName = 'La Belle Epoque';
    
        // Enregistrer la consultation avec le nom    
        $this->historiqueService->enregistrerConsultation('Restaurant', $restaurantId, $restaurantName);
    
        return $this->render('information/belle_epoque.html.twig');
}

    /**
     * Affiche les informations du restaurant La Petite Sirène.
     * Enregistre la consultation dans l'historique.
     * 
     * @return Response Réponse HTTP contenant la page du restaurant La Petite Sirène
     */
    #[Route('/restaurant/petite-sirene', name: 'restaurant_petite_sirene')]
    public function petiteSirene(): Response
    {
        $restaurantId = 3;
        $restaurantName = 'La Petite Sirène';
    
        $this->historiqueService->enregistrerConsultation('Restaurant', $restaurantId, $restaurantName);
    
        return $this->render('information/petite_sirene.html.twig');
    }
}
