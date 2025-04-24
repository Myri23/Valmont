<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\CameraSurveillance;
use App\Form\ObjetConnecteType;
use App\Form\CameraSurveillanceType;
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

        return $this->render('home/ajouter_objet.html.twig', [
            'form' => $form->createView(),
            'form_camera' => $formCamera->createView(),
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
