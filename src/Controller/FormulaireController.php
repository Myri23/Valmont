<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormulaireController extends AbstractController
{
    #[Route('/formulaire', name: 'formulaire')]
    public function formulaire(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo_url')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur de téléchargement si nécessaire
                }

                $utilisateur->setPhotoUrl($newFilename);
            }

            // Champs non inclus dans le formulaire à remplir ici :
            $utilisateur->setTypeUtilisateur('visiteur');
            $utilisateur->setNiveauExperience('débutant');
            $utilisateur->setPointsConnexion(0);
            $utilisateur->setPointsConsultation(0);
            $utilisateur->setCompteValide(false);
            $utilisateur->setDateInscription(new \DateTime());

            $em->persist($utilisateur);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('formulaire');
        }

        return $this->render('home/formulaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
