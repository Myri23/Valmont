<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\CameraSurveillance;
use App\Entity\ParkingIntelligent;
use App\Entity\CapteurBruit;
use App\Entity\AbribusIntelligent;
use App\Form\ObjetConnecteType;
use App\Form\CameraSurveillanceType;
use App\Form\ParkingIntelligentType;
use App\Form\CapteurBruitType;
use App\Form\AbribusIntelligentType;
use App\Repository\ObjetConnecteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ObjetConnecteController extends AbstractController
{
    #[Route('/ajouter_objet', name: 'ajouter_objet')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $objet = new ObjetConnecte();
        $form = $this->createForm(ObjetConnecteType::class, $objet);
        $form->handleRequest($request);

        $camera = new CameraSurveillance();
        $formCamera = $this->createForm(CameraSurveillanceType::class, $camera);
        $formCamera->handleRequest($request);
        
        $parking = new ParkingIntelligent();
        $formParking = $this->createForm(ParkingIntelligentType::class, $parking);
        $formParking->handleRequest($request);
        
        $capteur = new CapteurBruit();
        $formCapteur = $this->createForm(CapteurBruitType::class, $capteur);
        $formCapteur->handleRequest($request);
        
        $abribus = new AbribusIntelligent();
        $formAbribus = $this->createForm(AbribusIntelligentType::class, $abribus);
        $formAbribus->handleRequest($request);

        if ($form->get('saveObjet')->isClicked() && $form->isValid()) {
            $em->persist($objet);
            $em->flush();
            $this->addFlash('success', 'Objet ajouté avec succès !');
            return $this->redirectToRoute('ajouter_objet');
        }

        if ($formCamera->get('saveCamera')->isClicked() && $formCamera->isValid()) {
            if (!$camera->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour la caméra.');
            } else {
                $em->persist($camera);
                $em->flush();
                $this->addFlash('success', 'Caméra ajoutée avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }
        
        if ($formParking->get('saveParking')->isClicked() && $formParking->isValid()) {
            if (!$parking->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour le parking.');
            } else {
                $em->persist($parking);
                $em->flush();
                $this->addFlash('success', 'Parking intelligent ajouté avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }
        
        if ($formCapteur->get('saveCapteur')->isClicked() && $formCapteur->isValid()) {
            if (!$capteur->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour le capteur de bruit.');
            } else {
                $em->persist($capteur);
                $em->flush();
                $this->addFlash('success', 'Capteur de bruit ajouté avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }
        
        if ($formAbribus->get('saveAbribus')->isClicked() && $formAbribus->isValid()) {
            if (!$abribus->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour l\'abribus intelligent.');
            } else {
                $em->persist($abribus);
                $em->flush();
                $this->addFlash('success', 'Abribus intelligent ajouté avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }

        return $this->render('home/ajouter_objet.html.twig', [
            'form' => $form->createView(),
            'form_camera' => $formCamera->createView(),
            'form_parking' => $formParking->createView(),
            'form_capteur' => $formCapteur->createView(),
            'form_abribus' => $formAbribus->createView(),
        ]);
    }

    #[Route('/objets', name: 'objets')]
    public function liste(ObjetConnecteRepository $repository): Response
    {
        $objets = $repository->findAll();

        return $this->render('home/objets.html.twig', [
            'objets' => $objets,
        ]);
    }
}