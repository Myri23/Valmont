<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/ville', name: 'ville')]
    public function ville()
    {
        return $this->render('home/ville.html.twig');
    }

    #[Route('/lieux_interet', name: 'lieux_interet')]
    public function lieux_interet()
    {
        return $this->render('home/lieux_interet.html.twig');
    }

    
    #[Route('/event', name: 'event')]
    public function event()
    {
        return $this->render('home/event.html.twig');
    }

    #[Route('/bus', name: 'bus')]
    public function bus()
    {
        return $this->render('home/bus.html.twig');
    }

    #[Route('/tram', name: 'tram')]
    public function tram()
    {
        return $this->render('home/tram.html.twig');
    }

    #[Route('/metro', name: 'metro')]
    public function metro()
    {
        return $this->render('home/metro.html.twig');
    }

    #[Route('/bibliotheque', name: 'bibliotheque')]
    public function bibliotheque()
    {
        return $this->render('home/bibliotheque.html.twig');
    }

    #[Route('/concert_jazz', name: 'concert_jazz')]
    public function concert_jazz()
    {
        return $this->render('home/concert_jazz.html.twig');
    }

    #[Route('/festival_talent', name: 'festival_talent')]
    public function festival_talent()
    {
        return $this->render('home/festival_talent.html.twig');
    }

    #[Route('/musees', name: 'musees')]
    public function musees()
    {
        return $this->render('home/musees.html.twig');
    }

    #[Route('/objets', name: 'objets')]
    public function objets()
    {
        return $this->render('home/objets.html.twig');
    }

    #[Route('/parcs', name: 'parcs')]
    public function parcs()
    {
        return $this->render('home/parcs.html.twig');
    }

    #[Route('/restaurants', name: 'restaurants')]
    public function restaurants()
    {
        return $this->render('home/restaurants.html.twig');
    }

    #[Route('/tableaux_vivants', name: 'tableaux_vivants')]
    public function tableaux_vivants()
    {
        return $this->render('home/tableaux_vivants.html.twig');
    }

    #[Route('/expo_valmont', name: 'expo_valmont')]
    public function expo_valmont()
    {
        return $this->render('home/expo_valmont.html.twig');
    }

    #[Route('/chasse_oeufs', name: 'chasse_oeufs')]
    public function chasse_oeufs()
    {
        return $this->render('home/chasse_oeufs.html.twig');
    }
    
    #[Route('/objets/ajouter', name: 'ajouter_objet')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $objet = new ObjetConnecte();
        $form = $this->createForm(ObjetConnecteType::class, $objet);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($objet);
            $em->flush();
    
            return $this->redirectToRoute('ajouter_objet');
        }
    
        return $this->render('objets/ajouter_objet.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   

}

