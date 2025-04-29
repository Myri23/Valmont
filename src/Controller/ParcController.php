<?php

// src/Controller/ParcController.php
namespace App\Controller;

use App\Service\HistoriqueConsultationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Contrôleur pour la gestion des pages relatives aux parcs
 * 
 * Ce contrôleur permet d'afficher les informations sur les parcs
 * et enregistre les consultations des utilisateurs
 */
class ParcController extends AbstractController
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
     * Affiche la page principale des parcs et espaces verts
     * 
     * @return Response La réponse HTTP
     */
     #[Route('/parcs', name: 'parcs')]
     public function index(): Response
     {
        // Enregistrer la consultation pour la page d'index des parcs
        $this->historiqueService->enregistrerConsultation('Catégorie', 1, 'Liste des parcs et espaces verts');
    
        return $this->render('information/parcs.html.twig');
    }
}
