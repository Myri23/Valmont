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
* Contrôleur pour la gestion des pages des musées
* 
* Ce contrôleur permet d'afficher les informations sur les différents
* musées de la ville et d'enregistrer les consultations des utilisateurs
*/
class MuseeController extends AbstractController
{
    private $security;
    private $entityManager;
    private $historiqueService;

   /**
    * Constructeur du contrôleur avec injection des dépendances
    * 
    * @param Security $security Service de sécurité Symfony
    * @param EntityManagerInterface $entityManager Gestionnaire d'entités
    * @param HistoriqueConsultationService $historiqueService Service d'historique
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
    * Affiche la page principale des musées
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musees', name: 'musees')]
    public function index(): Response
    {
        return $this->render('information/musees.html.twig');
    }

   /**
    * Affiche les informations du musée d'Histoire de Valmont
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musee/histoire', name: 'musee_histoire')]
    public function histoire(): Response
    {
        $museeId = 1;
        $museeName = 'Musée d\'Histoire de Valmont';
        
        // Enregistrer la consultation avec le nom
        $this->historiqueService->enregistrerConsultation('Musée', $museeId, $museeName);
        
        return $this->render('information/musee_histoire.html.twig');
    }

   /**
    * Affiche les informations du musée Sciences & Nature
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musee/science', name: 'musee_science')]
    public function science(): Response
    {
        $museeId = 2;
        $museeName = 'Musée Sciences & Nature';

        // Enregistrer la consultation avec le nom
        $this->historiqueService->enregistrerConsultation('Musée', $museeId, $museeName);
        
        return $this->render('information/musee_science.html.twig');
    }

  /**
    * Affiche les informations du musée du Citronnier
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musee/citronnier', name: 'musee_citronnier')]
    public function citronnier(): Response
    {
        $museeId = 3;
        $museeName = 'Musée du Citronnier';
  
        // Enregistrer la consultation avec le nom  
        $this->historiqueService->enregistrerConsultation('Musée', $museeId, $museeName);
        
        return $this->render('information/musee_citronnier.html.twig');
    }
}
