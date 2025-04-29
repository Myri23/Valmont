<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\ParkingIntelligent;
use App\Form\ObjetConnecteType;
use App\Form\ParkingIntelligentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des parkings intelligents
 */
class ParkingIntelligentController extends AbstractController
{
    /**
     * Crée un nouveau parking intelligent
     * 
     * Cette méthode permet de créer à la fois un objet connecté
     * et un parking intelligent associé à cet objet
     * 
     * @param Request $request La requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @return Response Réponse HTTP
     */
    #[Route('/objets/ajouter-parking', name: 'app_parking_intelligent_new')]
    public function newParking(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel objet connecté
        $objetConnecte = new ObjetConnecte();
        
        $objetConnecte->setType('Parking');
        
        // Création d'un nouveau parking intelligent
        $parkingIntelligent = new ParkingIntelligent();
        
        // Créer un seul formulaire combiné
        $form = $this->createFormBuilder(['objet' => $objetConnecte, 'parking' => $parkingIntelligent])
            ->add('objet', ObjetConnecteType::class)
            ->add('parking', ParkingIntelligentType::class)
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer l'objet connecté
            $entityManager->persist($objetConnecte);
            $entityManager->flush();
            
            // Associer le parking à l'objet connecté après avoir persisté l'objet
            $parkingIntelligent->setObjet($objetConnecte);
            
            // Enregistrer le parking intelligent
            $entityManager->persist($parkingIntelligent);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le parking intelligent a été ajouté avec succès.');
            
            return $this->redirectToRoute('app_objets_list');
        }
        
        return $this->render('gestion/new_parking.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
