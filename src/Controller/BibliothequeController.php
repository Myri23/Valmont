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

class BibliothequeController extends AbstractController
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

#[Route('/bibliotheque', name: 'bibliotheques')]
public function index(): Response
{
    // Enregistrer la consultation pour la page d'index des bibliothèques
    $this->historiqueService->enregistrerConsultation('Bibliothèque', 4, 'Liste des bibliothèques');
    
    return $this->render('information/bibliotheque.html.twig');
}
    

}
