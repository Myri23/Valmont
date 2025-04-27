<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\PoubelleConnectee;
use App\Form\ObjetConnecteType;
use App\Form\PoubelleConnecteeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjetConnecteController extends AbstractController
{
#[Route('/gestion/objets', name: 'app_objets_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $objets = $entityManager->getRepository(ObjetConnecte::class)->findAll();
        
        return $this->render('gestion/index.html.twig', [
            'objets' => $objets,
        ]);
    }

    #[Route('/objets/select-type', name: 'app_objets_select_type')]
    public function selectType(): Response
    {
        return $this->render('gestion/select_type.html.twig');
    }

    #[Route('/objets/ajouter-poubelle', name: 'app_poubelle_connectee_new')]
    public function newPoubelle(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel objet connecté (parent)
        $objetConnecte = new ObjetConnecte();
        $objetConnecteForm = $this->createForm(ObjetConnecteType::class, $objetConnecte);
        $objetConnecteForm->handleRequest($request);
        
        // Création d'une nouvelle poubelle connectée
        $poubelleConnectee = new PoubelleConnectee();
        $poubelleForm = $this->createForm(PoubelleConnecteeType::class, $poubelleConnectee);
        $poubelleForm->handleRequest($request);
        
        if ($objetConnecteForm->isSubmitted() && $objetConnecteForm->isValid() && 
            $poubelleForm->isSubmitted() && $poubelleForm->isValid()) {
            
            // Définir le type d'objet comme "Poubelle"
            $objetConnecte->setType('Poubelle');
            
            // Enregistrer l'objet connecté
            $entityManager->persist($objetConnecte);
            $entityManager->flush();
            
            // Associer la poubelle à l'objet connecté
            $poubelleConnectee->setObjet($objetConnecte);
            
            // Enregistrer la poubelle connectée
            $entityManager->persist($poubelleConnectee);
            $entityManager->flush();
            
            $this->addFlash('success', 'La poubelle connectée a été ajoutée avec succès.');
            
            return $this->redirectToRoute('app_objets_list');
        }
        
        return $this->render('gestion/new_poubelle.html.twig', [
            'objetConnecteForm' => $objetConnecteForm->createView(),
            'poubelleForm' => $poubelleForm->createView(),
        ]);
    }
}
