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

class MuseeController extends AbstractController
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

    #[Route('/musees', name: 'musees')]
    public function index(): Response
    {
        return $this->render('information/musees.html.twig');
    }

    #[Route('/musee/histoire', name: 'musee_histoire')]
    public function histoire(): Response
    {
        // ID du musée d'histoire
        $museeId = 1;
        $museeName = 'Musée d\'Histoire de Valmont';
        
        // Enregistrer la consultation avec le nom
        $this->historiqueService->enregistrerConsultation('Musée', $museeId, $museeName);
        
        return $this->render('information/musee_histoire.html.twig');
    }

    #[Route('/musee/science', name: 'musee_science')]
    public function science(): Response
    {
        $museeId = 2;
        $museeName = 'Musée Sciences & Nature';
        
        $this->historiqueService->enregistrerConsultation('Musée', $museeId, $museeName);
        
        return $this->render('information/musee_science.html.twig');
    }

    #[Route('/musee/citronnier', name: 'musee_citronnier')]
    public function citronnier(): Response
    {
        $museeId = 3;
        $museeName = 'Musée du Citronnier';
        
        $this->historiqueService->enregistrerConsultation('Musée', $museeId, $museeName);
        
        return $this->render('information/musee_citronnier.html.twig');
    }
}
