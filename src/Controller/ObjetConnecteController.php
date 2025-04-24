<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\CameraSurveillance;
use App\Entity\BorneRecharge;
use App\Entity\CapteurQualiteAir;
use App\Entity\FeuCirculation;
use App\Entity\LampadaireIntelligent;
use App\Entity\PoubelleConnectee;
use App\Form\ObjetConnecteType;
use App\Form\CameraSurveillanceType;
use App\Form\BorneRechargeType;
use App\Form\CapteurQualiteAirType;
use App\Form\FeuCirculationType;
use App\Form\LampadaireIntelligentType;
use App\Form\PoubelleConnecteeType;
use App\Repository\ObjetConnecteRepository;
use App\Repository\CameraSurveillanceRepository;
use App\Repository\BorneRechargeRepository;
use App\Repository\CapteurQualiteAirRepository;
use App\Repository\FeuCirculationRepository;
use App\Repository\LampadaireIntelligentRepository;
use App\Repository\PoubelleConnecteeRepository;
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

        $borneRecharge = new BorneRecharge();
        $formBorneRecharge = $this->createForm(BorneRechargeType::class, $borneRecharge);
        $formBorneRecharge->handleRequest($request);

        $capteurQualiteAir = new CapteurQualiteAir();
        $formCapteurQualiteAir = $this->createForm(CapteurQualiteAirType::class, $capteurQualiteAir);
        $formCapteurQualiteAir->handleRequest($request);

        $feuCirculation = new FeuCirculation();
        $formFeuCirculation = $this->createForm(FeuCirculationType::class, $feuCirculation);
        $formFeuCirculation->handleRequest($request);

        $lampadaireIntelligent = new LampadaireIntelligent();
        $formLampadaireIntelligent = $this->createForm(LampadaireIntelligentType::class, $lampadaireIntelligent);
        $formLampadaireIntelligent->handleRequest($request);

        $poubelleConnectee = new PoubelleConnectee();
        $formPoubelleConnectee = $this->createForm(PoubelleConnecteeType::class, $poubelleConnectee);
        $formPoubelleConnectee->handleRequest($request);

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

        if ($formBorneRecharge->get('saveBorneRecharge')->isClicked() && $formBorneRecharge->isValid()) {

            if (!$borneRecharge->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour la borne de recharge.');
            } else {
                $em->persist($borneRecharge);
                $em->flush();
                $this->addFlash('success', 'Borne de recharge ajoutée avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }

        if ($formCapteurQualiteAir->get('saveCapteurQualiteAir')->isClicked() && $formCapteurQualiteAir->isValid()) {

            if (!$capteurQualiteAir->getObjet()) {
                $this->addFlash('error', "Veuillez sélectionner un objet connecté pour le Capteur de Qualite d'Air.");
            } else {
                $em->persist($capteurQualiteAir);
                $em->flush();
                $this->addFlash('success', "Capteur de Qualite d'Air ajouté avec succès !");
                return $this->redirectToRoute('ajouter_objet');
            }
        }

        if ($formFeuCirculation->get('saveFeuCirculation')->isClicked() && $formFeuCirculation->isValid()) {

            if (!$feuCirculation->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour le feu de circulation.');
            } else {
                $em->persist($feuCirculation);
                $em->flush();
                $this->addFlash('success', 'Feu de circulation ajouté avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }

        if ($formLampadaireIntelligent->get('saveLampadaireIntelligent')->isClicked() && $formLampadaireIntelligent->isValid()) {

            if (!$lampadaireIntelligent->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour le lampadaire intelligent.');
            } else {
                $em->persist($lampadaireIntelligent);
                $em->flush();
                $this->addFlash('success', 'Lampadaire intelligent ajouté avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }

        if ($formPoubelleConnectee->get('savePoubelleConnectee')->isClicked() && $formPoubelleConnectee->isValid()) {

            if (!$poubelleConnectee->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour la poubelle connectee.');
            } else {
                $em->persist($poubelleConnectee);
                $em->flush();
                $this->addFlash('success', 'Poubelle connectee ajoutée avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }

        return $this->render('home/ajouter_objet.html.twig', [
            'form' => $form->createView(),
            'form_camera' => $formCamera->createView(),
            'form_borneRecharge' => $formBorneRecharge->createView(),
            'form_capteur_qualite_air' => $formCapteurQualiteAir->createView(),
            'form_feu_circulation' => $formFeuCirculation->createView(),
            'form_lampadaire_intelligent' => $formLampadaireIntelligent->createView(),
            'form_poubelle_connectee' => $formPoubelleConnectee->createView(),
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
