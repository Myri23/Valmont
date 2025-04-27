<?php

// src/Controller/HomeController.php
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


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home()
    {
        $user = $this->getUser();  // Récupérer l'utilisateur connecté
        return $this->render('home/index.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/ajouter_objet', name: 'ajouter_objet')]
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
    
        return $this->render('home/ajouter_objet.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    


}

