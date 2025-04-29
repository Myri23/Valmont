<?php

namespace App\Controller;

use App\Entity\LampadaireIntelligent;
use App\Entity\ObjetConnecte;
use App\Form\LampadaireIntelligentType;
use App\Form\ObjetConnecteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/objets/lampadaires')]
class LampadaireController extends AbstractController
{
    #[Route('/', name: 'app_lampadaire_intelligent_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les lampadaires intelligents
        $lampadaires = $entityManager->getRepository(LampadaireIntelligent::class)->findAll();
        
        return $this->render('gestion/lampadaire_list.html.twig', [
            'lampadaires' => $lampadaires,
        ]);
    }
    
    #[Route('/{id}', name: 'app_lampadaire_intelligent_show', methods: ['GET'])]
    public function show(LampadaireIntelligent $lampadaire): Response
    {
        return $this->render('gestion/lampadaire_show.html.twig', [
            'lampadaire' => $lampadaire,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_lampadaire_intelligent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LampadaireIntelligent $lampadaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder(['objet' => $lampadaire->getObjet(), 'lampadaire' => $lampadaire])
            ->add('objet', ObjetConnecteType::class)
            ->add('lampadaire', LampadaireIntelligentType::class)
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'Le lampadaire intelligent a été modifié avec succès.');
            
            return $this->redirectToRoute('app_lampadaire_intelligent_list');
        }
        
        return $this->render('gestion/edit_lampadaire.html.twig', [
            'form' => $form->createView(),
            'lampadaire' => $lampadaire,
        ]);
    }
    
    #[Route('/{id}/delete', name: 'app_lampadaire_intelligent_delete', methods: ['GET'])]
    public function delete(LampadaireIntelligent $lampadaire, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'objet connecté associé
        $objet = $lampadaire->getObjet();
        
        // Supprimer le lampadaire
        $entityManager->remove($lampadaire);
        
        // Supprimer l'objet connecté
        $entityManager->remove($objet);
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Le lampadaire intelligent a été supprimé avec succès.');
        
        return $this->redirectToRoute('app_lampadaire_intelligent_list');
    }
}
