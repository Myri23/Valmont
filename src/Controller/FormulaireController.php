<?php

// src/Controller/FormulaireController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
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

/**
 * Contrôleur pour le formulaire d'inscription et la confirmation de compte
 * 
 * Ce contrôleur gère l'inscription des utilisateurs, l'envoi des emails de confirmation,
 * et le processus de vérification des comptes par les administrateurs
*/
class FormulaireController extends AbstractController
{
    /**
     * Affiche et traite le formulaire d'inscription
     * 
     * Cette méthode affiche le formulaire d'inscription, traite les données soumises,
     * crée le nouvel utilisateur et envoie les emails de confirmation nécessaires
     * 
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $em Gestionnaire d'entités Doctrine
     * @param SluggerInterface $slugger Utilitaire pour générer des slugs sécurisés
     * @param UserPasswordHasherInterface $passwordHasher Service de hachage de mot de passe
     * @param MailerInterface $mailer Service d'envoi d'emails
     * @param UtilisateurRepository $utilisateurRepository Repository des utilisateurs
     * @return Response La réponse HTTP
     */
    #[Route('/formulaire', name: 'formulaire')]
    public function formulaire(
        Request $request, 
        EntityManagerInterface $em, 
        SluggerInterface $slugger, 
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer,
        UtilisateurRepository $utilisateurRepository
    ): Response
    {
        /* Création d'une nouvelle instance d'utilisateur */    
        $user = new Utilisateur();
        /* Création du formulaire basé sur l'utilisateur */        
        $form = $this->createForm(UtilisateurType::class, $user);
        /* Traitement de la requête pour remplir le formulaire */        
        $form->handleRequest($request);

        /* Vérification si le formulaire est soumis et valide */
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

            /* 
             * Initialisation des champs par défaut non inclus dans le formulaire
             * Définition du type d'utilisateur comme visiteur et niveau débutant
             */
            $user->setTypeUtilisateur('visiteur');
            $user->setNiveauExperience('débutant');
            $user->setPointsConnexion(0);
            $user->setPointsConsultation(0);
            $user->setDateInscription(new \DateTime());
            
            /* 
             * Initialisation du statut de vérification
             * Le compte n'est pas encore validé par un administrateur
             */
            $user->setStatutVerification('en_attente');
            $user->setCompteValide(false);
            
            /* 
             * Génération d'un token unique pour la confirmation par email
             * Utilisation de random_bytes pour une sécurité optimale
             */
            $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
            $user->setConfirmationToken($token);
            $user->setIsConfirmed(false);

            /* Enregistrement de l'utilisateur dans la base de données */
            $em->persist($user);
            $em->flush();

            /* Envoi de l'email de confirmation à l'utilisateur */
            $this->sendConfirmationEmail($user, $mailer);
            
            /* Envoi d'un email aux administrateurs pour la vérification */
            $this->sendAdminVerificationEmail($user, $mailer, $utilisateurRepository);

            /* Message de confirmation pour l'utilisateur */
            $this->addFlash('success', 'Inscription enregistrée ! Un email de confirmation a été envoyé à votre adresse. Votre compte sera validé par un administrateur après vérification de votre résidence.');
            /* Redirection vers la page du formulaire avec le message de succès */            
            return $this->redirectToRoute('formulaire');
        }

        /* Affichage du formulaire si pas encore soumis ou contient des erreurs */
        return $this->render('visualisation/formulaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Envoie l'email de confirmation à l'utilisateur
     * 
     * @param Utilisateur $user Utilisateur à confirmer
     * @param MailerInterface $mailer Service d'envoi d'emails
     * @return void
     */
    private function sendConfirmationEmail(Utilisateur $user, MailerInterface $mailer): void
    {
        $confirmationUrl = $this->generateUrl('confirm_account', 
            ['token' => $user->getConfirmationToken()], 
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        /* 
         * Création de l'email avec template Twig
         * Inclusion des informations nécessaires dans le contexte
         */
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

    /**
     * Envoie l'email de vérification aux administrateurs
     * 
     * @param Utilisateur $user Utilisateur à vérifier
     * @param MailerInterface $mailer Service d'envoi d'emails
     * @param UtilisateurRepository $utilisateurRepository Repository des utilisateurs
     * @return void
     */
    private function sendAdminVerificationEmail(Utilisateur $user, MailerInterface $mailer, UtilisateurRepository $utilisateurRepository): void
    {
        /* Récupération de tous les utilisateurs avec rôle administrateur */
        $admins = $utilisateurRepository->findByRole('ROLE_ADMIN');

        /* Si aucun administrateur n'est trouvé, utiliser une adresse par défaut */
        if (empty($admins)) {
            $admins = [['email' => 'admin@valmont-city.com']];
        }

        /* Génération de l'URL de vérification pour l'administrateur */
        $verificationUrl = $this->generateUrl('admin_verify_user', 
            ['id' => $user->getId()], 
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        /* Envoi d'un email à chaque administrateur */
        foreach ($admins as $admin) {
            $email = (new TemplatedEmail())
                ->from('valmontcitynoreply@gmail.com')
                ->to($admin['email'])
                ->subject('Nouvelle inscription à vérifier - Valmont')
                ->htmlTemplate('admin/verification_email.html.twig')
                ->context([
                    'verificationUrl' => $verificationUrl,
                    'user' => $user,
                    'adminName' => $admin['prenom'] ?? 'Administrateur'
                ]);

            $mailer->send($email);
        }
    }

    /**
     * Traite la confirmation du compte par l'utilisateur
     * 
     * @param string $token Token de confirmation unique
     * @param EntityManagerInterface $em Gestionnaire d'entités Doctrine
     * @param UtilisateurRepository $userRepository Repository des utilisateurs
     * @return Response La réponse HTTP
     */
    #[Route('/confirmer-compte/{token}', name: 'confirm_account')]
    public function confirmAccount(
        string $token, 
        EntityManagerInterface $em, 
        UtilisateurRepository $userRepository
    ): Response
    {
        /* Recherche de l'utilisateur correspondant au token */    
        $user = $userRepository->findOneBy(['confirmationToken' => $token]);

        /* Si aucun utilisateur trouvé, le token est invalide ou expiré */
        if (!$user) {
            $this->addFlash('error', 'Lien de confirmation invalide ou expiré.');
            return $this->redirectToRoute('formulaire');
        }
        
        /* Marquer l'email comme confirmé et supprimer le token */
        $user->setIsConfirmed(true);
        $user->setConfirmationToken(null);
        $em->flush();

        /* Message de confirmation pour l'utilisateur */
        $this->addFlash('success', 'Votre adresse email a été confirmée avec succès. Votre compte est maintenant en attente de vérification par un administrateur.');

        /* Affichage de la page de confirmation */
        return $this->render('visualisation/confirmation_success.html.twig', [
            'user' => $user
        ]);
    }
}
