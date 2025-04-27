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

    #[Route('/bibliotheques', name: 'bibliotheques')]
    public function index(): Response
    {
        return $this->render('information/bibliotheque.html.twig');
    }
    
    // Ajoutez cette route pour correspondre au lien dans lieux_interet.html.twig
    #[Route('/bibliotheque', name: 'bibliotheque')]
    public function bibliothequeIndex(): Response
    {
        try {
            // Enregistrer la consultation pour la page d'index des bibliothèques
            $this->historiqueService->enregistrerConsultation('Catégorie', 4, 'Liste des bibliothèques');
            
            // Rediriger vers la route bibliotheques 
            return $this->redirectToRoute('bibliotheques');
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur dans bibliotheque: " . $e->getMessage());
            
            // Retourner une réponse d'erreur avec plus de détails
            return new Response(
                'Une erreur s\'est produite: ' . $e->getMessage() . 
                '<br><br>Trace: <pre>' . $e->getTraceAsString() . '</pre>'
            );
        }
    }
}
