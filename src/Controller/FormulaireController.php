<?php

// src/Controller/FormulaireController.php

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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; 
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class FormulaireController extends AbstractController
{
    #[Route('/formulaire', name: 'formulaire')]
public function formulaire(
    Request $request, 
    EntityManagerInterface $em, 
    SluggerInterface $slugger, 
    UserPasswordHasherInterface $passwordHasher,
    MailerInterface $mailer
): Response
{
    $user = new Utilisateur();
    $form = $this->createForm(UtilisateurType::class, $user);
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

            $user->setPhotoUrl($newFilename);
        }

        // Hachage du mot de passe
        $user->setMotDePasse($passwordHasher->hashPassword($user, $user->getMotDePasse()));

        // Champs non inclus dans le formulaire à remplir ici :
        $user->setTypeUtilisateur('visiteur');
        $user->setNiveauExperience('débutant');
        $user->setPointsConnexion(0);
        $user->setPointsConsultation(0);
        $user->setCompteValide(false);
        $user->setDateInscription(new \DateTime());
        
        // Générer un token unique pour la confirmation
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $user->setConfirmationToken($token);
        $user->setIsConfirmed(false);

        $em->persist($user);
        $em->flush();

        // Envoyer l'email de confirmation
        $this->sendConfirmationEmail($user, $mailer);

        $this->addFlash('success', 'Inscription réussie ! Un email de confirmation a été envoyé à votre adresse email.');
        return $this->redirectToRoute('formulaire');
    }

    return $this->render('visualisation/formulaire.html.twig', [
        'form' => $form->createView(),
    ]);
}

private function sendConfirmationEmail(Utilisateur $user, MailerInterface $mailer): void
{
    $confirmationUrl = $this->generateUrl('confirm_account', 
        ['token' => $user->getConfirmationToken()], 
        UrlGeneratorInterface::ABSOLUTE_URL
    );

    $email = (new TemplatedEmail())
        ->from('valmontcitynoreply@gmail.com')
        ->to($user->getEmail())
        ->subject('Confirmer votre compte sur Valmont')
        ->htmlTemplate('visualisation/confirmation.html.twig')
        ->context([
            'confirmationUrl' => $confirmationUrl,
            'user' => $user,
            'expiration_date' => new \DateTime('+24 hours')
        ]);

    $mailer->send($email);
}
}