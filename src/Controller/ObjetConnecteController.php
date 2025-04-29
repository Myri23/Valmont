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

/**
* Contrôleur pour la gestion des objets connectés
* 
* Ce contrôleur permet de lister, sélectionner et créer 
* différents types d'objets connectés dans le système
*/
class ObjetConnecteController extends AbstractController
{
   /**
    * Affiche la liste de tous les objets connectés
    * 
    * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
    * @return Response Réponse HTTP
    */
    #[Route('/gestion/objets', name: 'app_objets_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $objets = $entityManager->getRepository(ObjetConnecte::class)->findAll();
        
        return $this->render('gestion/index.html.twig', [
            'objets' => $objets,
        ]);
    }
   
   /**
    * Affiche la page de sélection du type d'objet connecté à créer
    * 
    * @return Response Réponse HTTP
    */    
    #[Route('/objets/select-type', name: 'app_objets_select_type')]
    public function selectType(): Response
    {
        return $this->render('gestion/select_type.html.twig');
    }

   /**
    * Crée une nouvelle poubelle connectée
    * 
    * @param Request $request La requête HTTP
    * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
    * @return Response Réponse HTTP
    */
    #[Route('/objets/ajouter-poubelle', name: 'app_poubelle_connectee_new')]
    public function newPoubelle(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel objet connecté
        $objetConnecte = new ObjetConnecte();
        
        $objetConnecte->setType('Poubelle');
        
        $poubelleConnectee = new PoubelleConnectee();
        
       /* 
        * Création d'un formulaire combiné qui contient
        * à la fois l'objet connecté et la poubelle connectée
        */
        $form = $this->createFormBuilder(['objet' => $objetConnecte, 'poubelle' => $poubelleConnectee])
            ->add('objet', ObjetConnecteType::class)
            ->add('poubelle', PoubelleConnecteeType::class)
            ->getForm();
        
        $form->handleRequest($request);
       /* Vérification si le formulaire est soumis et valide */        
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer l'objet connecté
            $entityManager->persist($objetConnecte);
            $entityManager->flush();
            
            // Associer la poubelle à l'objet connecté après avoir persisté l'objet
            $poubelleConnectee->setObjet($objetConnecte);
            
            // Enregistrer la poubelle connectée
            $entityManager->persist($poubelleConnectee);
            $entityManager->flush();
            
            $this->addFlash('success', 'La poubelle connectée a été ajoutée avec succès.');
            
            return $this->redirectToRoute('app_objets_list');
        }
       /* 
        * Affichage du formulaire si celui-ci n'est pas encore soumis
        * ou s'il contient des erreurs de validation
        */        
        return $this->render('gestion/new_poubelle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
