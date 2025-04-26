<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\CameraSurveillance;
use App\Entity\ParkingIntelligent;
use App\Entity\CapteurBruit;
use App\Entity\AbribusIntelligent;
use App\Entity\BorneRecharge;
use App\Entity\CapteurQualiteAir;
use App\Entity\FeuCirculation;
use App\Entity\LampadaireIntelligent;
use App\Entity\PoubelleConnectee;
use App\Entity\Zone;
use App\Form\ObjetConnecteType;
use App\Form\CameraSurveillanceType;
use App\Form\ParkingIntelligentType;
use App\Form\CapteurBruitType;
use App\Form\AbribusIntelligentType;
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
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
        $formCapteurBruit = $this->createForm(CapteurBruitType::class, $capteurBruit);
        $formCapteurBruit->handleRequest($request);
        
        $abribus = new AbribusIntelligent();
        $formAbribus = $this->createForm(AbribusIntelligentType::class, $abribus);
        $formAbribus->handleRequest($request);

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
        
        if ($formCapteurBruit->get('saveCapteurBruit')->isClicked() && $formCapteurBruit->isValid()) {
            if (!$capteurBruit->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour le capteur de bruit.');
            } else {
                $em->persist($capteurBruit);
                $em->flush();
                $this->addFlash('success', 'Capteur de bruit ajouté avec succès !');
                return $this->redirectToRoute('ajouter_objet');
            }
        }
        
        if ($formAbribus->get('saveAbribus')->isClicked() && $formAbribus->isValid()) {
            if (!$abribus->getObjet()) {
                $this->addFlash('error', 'Veuillez sélectionner un objet connecté pour l\'abribus intelligent.');
            } else {
                $zone = $formAbribus->get('zone')->getData();
                if ($zone) {
                    $abribus->getObjet()->setZone($zone);
                }
                $em->persist($abribus);
                $em->flush();
                $this->addFlash('success', 'Abribus intelligent ajouté avec succès !');
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
            'form_parking' => $formParking->createView(),
            'form_capteur_bruit' => $formCapteurBruit->createView(),
            'form_abribus' => $formAbribus->createView(),
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
        if (!$this->getUser()) {
            $this->addFlash('error', 'Veuillez vous connecter pour accéder à cette page.');
            return $this->redirectToRoute('connexion'); 
        }

        $objets = $repository->findAll();

        return $this->render('home/objets.html.twig', [
            'objets' => $objets,
        ]);
    }

    #[Route('/objets/{id}', name: 'objets_show', methods: ['GET'])]
    public function show(ObjetConnecte $objet): Response
    {
        return $this->render('home/objets_show.html.twig', [
            'objet' => $objet,
        ]);
    }

    #[Route('/objets/{id}/edit', name: 'objets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ObjetConnecte $objet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjetConnecteType::class, $objet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Objet modifié avec succès !');
            return $this->redirectToRoute('objets');
        }

        return $this->render('home/objets_edit.html.twig', [
            'objet' => $objet,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/objets/{id}/toggle-status', name: 'objets_toggle_status', methods: ['POST'])]
    public function toggleStatus(ObjetConnecte $objet, EntityManagerInterface $entityManager): Response
    {
        // Inverser l'état actif/inactif de l'objet
        $objet->setActif(!$objet->isActif());
        $entityManager->flush();
        
        $this->addFlash('success', 'État de l\'objet modifié avec succès !');
        return $this->redirectToRoute('objets_show', ['id' => $objet->getId()]);
    }
    
    #[Route('/objets/type/{type}', name: 'objets_by_type')]
    public function listByType(string $type, ObjetConnecteRepository $repository): Response
    {
        $objets = $repository->findBy(['type' => $type]);
        
        return $this->render('home/objets.html.twig', [
            'objets' => $objets,
            'type_filtre' => $type
        ]);
    }
    
    #[Route('/objets/zone/{zoneId}', name: 'objets_by_zone')]
    public function listByZone(int $zoneId, ObjetConnecteRepository $repository, ZoneRepository $zoneRepository): Response
    {
        $zone = $zoneRepository->find($zoneId);
        
        if (!$zone) {
            $this->addFlash('error', 'Zone non trouvée.');
            return $this->redirectToRoute('objets');
        }
        
        $objets = $repository->findBy(['zone' => $zone]);
        
        return $this->render('home/objets.html.twig', [
            'objets' => $objets,
            'zone_filtre' => $zone->getNom()
        ]);
    }
}