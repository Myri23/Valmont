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
 * Contrôleur pour la gestion des pages des bibliothèques
 * 
 * Ce contrôleur permet d'afficher les informations sur les bibliothèques
 * et d'enregistrer les consultations des utilisateurs
 */
class BibliothequeController extends AbstractController
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
        /* Initialisation des services */    
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->historiqueService = $historiqueService;
    }

    /**
     * Affiche la page principale des bibliothèques
     * 
     * @return Response La réponse HTTP
     */
    #[Route('/bibliotheque', name: 'bibliotheques')]
    public function index(): Response
    {
        /* 
         * Enregistrement de la consultation dans l'historique
         * avec les paramètres: type, id et description
         */
        $this->historiqueService->enregistrerConsultation('Bibliothèque', 4, 'Liste des bibliothèques');

        /* Affichage du template pour la page des bibliothèques */
        return $this->render('information/bibliotheque.html.twig');
    }
    
}
