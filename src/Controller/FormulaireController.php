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
        // Code existant pour le traitement de la photo...
        
        // Hachage du mot de passe
        $user->setMotDePasse($passwordHasher->hashPassword($user, $user->getMotDePasse()));

        // Champs non inclus dans le formulaire
        $user->setTypeUtilisateur('visiteur');
        $user->setNiveauExperience('débutant');
        $user->setPointsConnexion(0);
        $user->setPointsConsultation(0);
        $user->setDateInscription(new \DateTime());
        
        // Statut initial de vérification
        $user->setStatutVerification('en_attente');
        $user->setCompteValide(false);
        
        // Générer un token unique pour la confirmation
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $user->setConfirmationToken($token);
        $user->setIsConfirmed(false);

        $em->persist($user);
        $em->flush();

        // Envoyer l'email de confirmation à l'utilisateur
        $this->sendConfirmationEmail($user, $mailer);
        
        // Envoyer un email à l'administrateur pour la vérification
        $this->sendAdminVerificationEmail($user, $mailer);

        $this->addFlash('success', 'Inscription enregistrée ! Un email de confirmation a été envoyé à votre adresse. Votre compte sera validé par un administrateur après vérification de votre résidence.');
        return $this->redirectToRoute('formulaire');
    }

    return $this->render('visualisation/formulaire.html.twig', [
        'form' => $form->createView(),
    ]);
}

private function sendAdminVerificationEmail(Utilisateur $user, MailerInterface $mailer): void
{
    // Récupérer l'email de l'admin (à configurer dans votre système)
    $adminEmail = 'admin@valmont-city.com';
    
    $verificationUrl = $this->generateUrl('admin_verify_user', 
        ['id' => $user->getId()], 
        UrlGeneratorInterface::ABSOLUTE_URL
    );

    $email = (new TemplatedEmail())
        ->from('valmontcitynoreply@gmail.com')
        ->to($adminEmail)
        ->subject('Nouvelle inscription à vérifier - Valmont')
        ->htmlTemplate('admin/verification_email.html.twig')
        ->context([
            'verificationUrl' => $verificationUrl,
            'user' => $user,
        ]);

    $mailer->send($email);
}

#[Route('/confirmer-compte/{token}', name: 'confirm_account')]
public function confirmAccount(
    string $token, 
    EntityManagerInterface $em, 
    UtilisateurRepository $userRepository
): Response
{
    $user = $userRepository->findOneBy(['confirmationToken' => $token]);
    
    if (!$user) {
        $this->addFlash('error', 'Lien de confirmation invalide ou expiré.');
        return $this->redirectToRoute('formulaire');
    }
    
    // Marquer l'email comme confirmé
    $user->setIsConfirmed(true);
    $user->setConfirmationToken(null);
    $em->flush();
    
    $this->addFlash('success', 'Votre adresse email a été confirmée avec succès. Votre compte est maintenant en attente de vérification par un administrateur.');
    
    return $this->render('visualisation/confirmation_success.html.twig', [
        'user' => $user
    ]);
}

}