<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\PoubelleConnectee;
use App\Entity\ParkingIntelligent;
use App\Entity\LampadaireIntelligent;

use App\Form\ObjetConnecteType;
use App\Form\PoubelleConnecteeType;
use App\Form\ParkingIntelligentType;
use App\Form\LampadaireIntelligentType;

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
        // Création d'un nouvel objet connecté
        $objetConnecte = new ObjetConnecte();
        
        // Pré-remplir le type - idUnique sera auto-incrémenté
        $objetConnecte->setType('Poubelle');
        
        // Création d'une nouvelle poubelle connectée
        $poubelleConnectee = new PoubelleConnectee();
        
        // Créer un seul formulaire combiné
        $form = $this->createFormBuilder(['objet' => $objetConnecte, 'poubelle' => $poubelleConnectee])
            ->add('objet', ObjetConnecteType::class)
            ->add('poubelle', PoubelleConnecteeType::class)
            ->getForm();
        
        $form->handleRequest($request);
        
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
        
        return $this->render('gestion/new_poubelle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
   #[Route('/objets/ajouter-parking', name: 'app_parking_intelligent_new')]
    public function newParking(Request $request, EntityManagerInterface $entityManager): Response
    {

        $objetConnecte = new ObjetConnecte();
        $objetConnecte->setType('Parking');
        $parkingIntelligent = new ParkingIntelligent();
        
        $form = $this->createFormBuilder(['objet' => $objetConnecte, 'parking' => $parkingIntelligent])
            ->add('objet', ObjetConnecteType::class)
            ->add('parking', ParkingIntelligentType::class)
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($objetConnecte);
            $entityManager->flush();
            
            $parkingIntelligent->setObjet($objetConnecte);
            
            $entityManager->persist($parkingIntelligent);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le parking intelligent a été ajouté avec succès.');
            
            return $this->redirectToRoute('app_objets_list');
        }
        
        return $this->render('gestion/new_parking.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/objets/ajouter-lampadaire', name: 'app_lampadaire_intelligent_new')]
public function newLampadaire(Request $request, EntityManagerInterface $entityManager): Response
{
    // Création d'un nouvel objet connecté
    $objetConnecte = new ObjetConnecte();
    
    // Pré-remplir le type - idUnique sera auto-incrémenté
    $objetConnecte->setType('Lampadaire');
    
    // Création d'un nouveau lampadaire intelligent
    $lampadaireIntelligent = new LampadaireIntelligent();
    
    // Créer un seul formulaire combiné
    $form = $this->createFormBuilder(['objet' => $objetConnecte, 'lampadaire' => $lampadaireIntelligent])
        ->add('objet', ObjetConnecteType::class)
        ->add('lampadaire', LampadaireIntelligentType::class)
        ->getForm();
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer l'objet connecté
        $entityManager->persist($objetConnecte);
        $entityManager->flush();
        
        // Associer le lampadaire à l'objet connecté après avoir persisté l'objet
        $lampadaireIntelligent->setObjet($objetConnecte);
        
        // Enregistrer le lampadaire intelligent
        $entityManager->persist($lampadaireIntelligent);
        $entityManager->flush();
        
        $this->addFlash('success', 'Le lampadaire intelligent a été ajouté avec succès.');
        
        return $this->redirectToRoute('app_objets_list');
    }
    
    return $this->render('gestion/new_lampadaire.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/objets/poubelles', name: 'app_poubelle_connectee_list')]
public function listPoubelles(EntityManagerInterface $entityManager): Response
{
    // Récupérer toutes les poubelles connectées
    $poubelles = $entityManager->getRepository(PoubelleConnectee::class)->findAll();
    
    return $this->render('gestion/poubelle_list.html.twig', [
        'poubelles' => $poubelles,
    ]);
}

#[Route('/objets/poubelles/{idUnique}', name: 'app_poubelle_connectee_show')]
public function showPoubelle(string $idUnique, EntityManagerInterface $entityManager): Response
{
    $poubelle = $entityManager->getRepository(PoubelleConnectee::class)
        ->findOneBy(['objet' => $entityManager->getRepository(ObjetConnecte::class)
        ->findOneBy(['idUnique' => $idUnique])]);
    
    if (!$poubelle) {
        throw $this->createNotFoundException('Poubelle non trouvée');
    }
    
    return $this->render('gestion/poubelle_show.html.twig', [
        'poubelle' => $poubelle,
    ]);
}

#[Route('/objets/poubelles/{idUnique}/edit', name: 'app_poubelle_connectee_edit')]
public function editPoubelle(string $idUnique, Request $request, EntityManagerInterface $entityManager): Response
{
    $objet = $entityManager->getRepository(ObjetConnecte::class)->findOneBy(['idUnique' => $idUnique]);

    if (!$objet) {
        throw $this->createNotFoundException('Objet connecté non trouvé.');
    }

    $poubelle = $entityManager->getRepository(PoubelleConnectee::class)->findOneBy(['objet' => $objet]);

    if (!$poubelle) {
        throw $this->createNotFoundException('Poubelle connectée non trouvée.');
    }

    // Formulaire combiné
    $form = $this->createFormBuilder(['objet' => $objet, 'poubelle' => $poubelle])
        ->add('objet', ObjetConnecteType::class)
        ->add('poubelle', PoubelleConnecteeType::class)
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'La poubelle connectée a été modifiée avec succès.');
        return $this->redirectToRoute('app_poubelle_connectee_list');
    }

    return $this->render('gestion/edit_poubelle.html.twig', [
        'form' => $form->createView(),
        'poubelle' => $poubelle,
    ]);
}

#[Route('/objets/poubelles/{id}/delete', name: 'app_poubelle_connectee_delete')]
public function deletePoubelle(Request $request, PoubelleConnectee $poubelle, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'objet connecté associé
    $objet = $poubelle->getObjet();
    
    // Supprimer la poubelle
    $entityManager->remove($poubelle);
    
    // Supprimer l'objet connecté (cela peut être fait automatiquement si vous avez configuré CASCADE)
    $entityManager->remove($objet);
    
    $entityManager->flush();
    
    $this->addFlash('success', 'La poubelle connectée a été supprimée avec succès.');
    
    return $this->redirectToRoute('app_poubelle_connectee_list');
}
    
}
