<?php

namespace App\Controller;

use App\Entity\HistoriqueConnexion;  
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Vérifie que seuls les administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Récupère tous les utilisateurs depuis la base de données
        $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();
        
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'utilisateurs' => $utilisateurs,
        ]);
    }
    
    #[Route('/admin/utilisateur/{id}/supprimer', name: 'admin_utilisateur_supprimer')]
    public function supprimer(Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifie que seuls les administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Vérifier si l'utilisateur essaie de se supprimer lui-même
        if ($utilisateur === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte !');
            return $this->redirectToRoute('admin');
        }
        
        // Supprime l'utilisateur
        $entityManager->remove($utilisateur);
        $entityManager->flush();
        
        // Ajoute un message flash pour confirmer la suppression
        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
        
        // Redirige vers la page d'administration
        return $this->redirectToRoute('admin');
    }
    
    #[Route('/admin/historique', name: 'admin_historique_connexion')]
    public function historiqueConnexions(EntityManagerInterface $entityManager): Response
    {
        // Utiliser le repository de l'entité HistoriqueConnexion
        $connexions = $entityManager->getRepository(HistoriqueConnexion::class)->findAll();

        return $this->render('admin/historique_connexion.html.twig', [
            'connexions' => $connexions,
        ]);
    }
}
