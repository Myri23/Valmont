<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ConnexionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function connexion(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ConnexionType::class);
        $form->handleRequest($request);

        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Recherche de l'utilisateur dans la base
            $user = $em->getRepository(Utilisateur::class)->findOneBy([
                'login' => $data['login'],
                'mot_de_passe' => $data['mot_de_passe'], // ⚠️ à sécuriser + hasher en vrai
            ]);

            if ($user) {
                $this->addFlash('success', 'Connexion réussie !');
                // ici tu peux enregistrer l'utilisateur en session par exemple
                return $this->redirectToRoute('home');
            } else {
                $error = 'Identifiants invalides.';
            }
        }

        return $this->render('home/connexion.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
