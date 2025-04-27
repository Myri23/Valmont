<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ObjetConnecte;
use App\Form\ObjetConnecteType;
use App\Form\ModifierProfilType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

final class InformationController extends AbstractController
{
    #[Route('/information', name: 'app_information')]
    public function index(): Response
    {
        return $this->render('information/index.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

    #[Route('/ville', name: 'ville')]
    public function ville()
    {
        return $this->render('information/ville.html.twig');
    }

    #[Route('/lieux_interet', name: 'lieux_interet')]
    public function lieux_interet()
    {
        return $this->render('information/lieux_interet.html.twig');
    }

    
    #[Route('/event', name: 'event')]
    public function event()
    {
        return $this->render('information/event.html.twig');
    }

    #[Route('/bus', name: 'bus')]
    public function bus()
    {
        return $this->render('information/bus.html.twig');
    }

    #[Route('/tram', name: 'tram')]
    public function tram()
    {
        return $this->render('information/tram.html.twig');
    }

    #[Route('/metro', name: 'metro')]
    public function metro()
    {
        return $this->render('information/metro.html.twig');
    }

    #[Route('/bibliotheque', name: 'bibliotheque')]
    public function bibliotheque()
    {
        return $this->render('information/bibliotheque.html.twig');
    }

    #[Route('/bibliotheque_centrale', name: 'bibliotheque_centrale')]
    public function bibliotheque_centrale()
    {
        return $this->render('information/bibliotheque_centrale.html.twig');
    }

    #[Route('/bibliotheque_jeunesse', name: 'bibliotheque_jeunesse')]
    public function bibliotheque_jeunesse()
    {
        return $this->render('information/bibliotheque_jeunesse.html.twig');
    }

    #[Route('/bibliotheque_universite', name: 'bibliotheque_universite')]
    public function bibliotheque_universite()
    {
        return $this->render('information/bibliotheque_universite.html.twig');
    }

    #[Route('/concert_jazz', name: 'concert_jazz')]
    public function concert_jazz()
    {
        return $this->render('information/concert_jazz.html.twig');
    }

    #[Route('/festival_talent', name: 'festival_talent')]
    public function festival_talent()
    {
        return $this->render('information/festival_talent.html.twig');
    }

    #[Route('/musees', name: 'musees')]
    public function musees()
    {
        return $this->render('information/musees.html.twig');
    }

    #[Route('/musee_histoire', name: 'musee_histoire')]
    public function musee_histoire()
    {
        return $this->render('information/musee_histoire.html.twig');
    }
    
    #[Route('/musee_science', name: 'musee_science')]
    public function musee_science()
    {
        return $this->render('information/musee_science.html.twig');
    }
        
    #[Route('/musee_citronnier', name: 'musee_citronnier')]
    public function musees_citronnier()
    {
        return $this->render('information/musee_citronnier.html.twig');
    }


    #[Route('/objets', name: 'objets')]
    public function objets()
    {
        return $this->render('information/objets.html.twig');
    }

    #[Route('/parcs', name: 'parcs')]
    public function parcs()
    {
        return $this->render('information/parcs.html.twig');
    }

    #[Route('/restaurants', name: 'restaurants')]
    public function restaurants()
    {
        return $this->render('information/restaurants.html.twig');
    }

    #[Route('/restaurant_queen', name: 'restaurant_queen')]
    public function restaurant_queen()
    {
        return $this->render('information/queen.html.twig');
    }

    #[Route('/restaurant_belle_epoque', name: 'restaurant_belle_epoque')]
    public function restaurant_belle_epoque()
    {
        return $this->render('information/belle_epoque.html.twig');
    }

    #[Route('/restaurant_petite_sirene', name: 'restaurant_petite_sirene')]
    public function restaurant_petite_sirene()
    {
        return $this->render('information/petite_sirene.html.twig');
    }

    #[Route('/tableaux_vivants', name: 'tableaux_vivants')]
    public function tableaux_vivants()
    {
        return $this->render('information/tableaux_vivants.html.twig');
    }

    #[Route('/expo_valmont', name: 'expo_valmont')]
    public function expo_valmont()
    {
        return $this->render('information/expo_valmont.html.twig');
    }

    #[Route('/chasse_oeufs', name: 'chasse_oeufs')]
    public function chasse_oeufs()
    {
        return $this->render('information/chasse_oeufs.html.twig');
    }
}
