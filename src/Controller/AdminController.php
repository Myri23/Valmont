<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Service\PointsService;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\HistoriqueConnexion;  
use App\Entity\HistoriqueConsultation;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * Contrôleur principal pour l'interface d'administration
 * 
 * Ce contrôleur final gère toutes les fonctionnalités réservées aux administrateurs,
 * comme la gestion des utilisateurs, les statistiques et les historiques
 */
final class AdminController extends AbstractController
{
    /**
     * Affiche le tableau de bord d'administration
     * 
     * Cette méthode affiche la liste des utilisateurs et sert
     * de point d'entrée pour l'interface d'administration
     * 
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @return Response La réponse HTTP
     */
    #[Route('/admin', name: 'admin')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /* Vérifie que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        /* Récupère tous les utilisateurs depuis la base de données */
        $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();

        /* Affichage du tableau de bord avec la liste des utilisateurs */
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'utilisateurs' => $utilisateurs,
        ]);
    }

    /**
     * Supprime un utilisateur et ses données associées
     * 
     * Cette méthode supprime un utilisateur après avoir vérifié
     * qu'il ne s'agit pas de l'administrateur actuel
     * 
     * @param Utilisateur $utilisateur Utilisateur à supprimer
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @param Request $request Requête HTTP
     * @return Response La réponse HTTP
     */
     #[Route('/admin/utilisateur/{id}/supprimer', name: 'admin_utilisateur_supprimer')]
    public function supprimer(Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
    {
        /* Vérifie que seuls les administrateurs peuvent accéder à cette page */    
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /* Empêche la suppression de son propre compte */
        if ($utilisateur === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte !');
            return $this->redirectToRoute('admin');
        }

        /* Supprime d'abord les historiques de connexion liés à cet utilisateur */
        $connexions = $entityManager->getRepository(\App\Entity\HistoriqueConnexion::class)->findBy(['utilisateur' => $utilisateur]);
    foreach ($connexions as $connexion) {
        $entityManager->remove($connexion);
    }

        /* Supprime l'utilisateur lui-même */
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        /* Message de confirmation pour l'administrateur */
        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

        /* Redirection vers le tableau de bord */
        return $this->redirectToRoute('admin');
    }


    /**
     * Affiche l'historique des connexions avec filtrage
     * 
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @return Response La réponse HTTP
     */
    #[Route('/admin/historique', name: 'admin_historique_connexion')]
    public function historiqueConnexions(Request $request, EntityManagerInterface $entityManager): Response
    {
        /* Vérifie que seuls les administrateurs peuvent accéder à cette page */    
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /* Récupérer tous les utilisateurs pour le filtrage */
        $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();
    
        /* Récupérer le filtre utilisateur */
        $userId = $request->query->get('utilisateur');
    
        /* Construire les critères de recherche */
        $criteria = [];
        if ($userId) {
            $criteria['utilisateur'] = $userId;
        }
    
        /* 
         * Récupérer les connexions avec tri par date 
         * (les plus récentes d'abord)
         */
        $connexions = $entityManager->getRepository(HistoriqueConnexion::class)
            ->findBy($criteria, ['dateConnexion' => 'DESC']);

        /* Affichage de l'historique avec les filtres */
        return $this->render('admin/historique_connexion.html.twig', [
            'connexions' => $connexions,
            'utilisateurs' => $utilisateurs,
            'selectedUser' => $userId
       ]);
    }

    /**
     * Modifie le niveau d'expérience et les points d'un utilisateur
     * 
     * @param Utilisateur $utilisateur Utilisateur à modifier
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @param PointsService $pointsService Service de gestion des points
     * @return Response La réponse HTTP
     */
    #[Route('/admin/utilisateur/{id}/modifier', name: 'admin_utilisateur_modifier')]
    public function modifierNiveauExperience(
        Utilisateur $utilisateur, 
        Request $request, 
        EntityManagerInterface $entityManager,
        PointsService $pointsService
    ): Response
    {
        /* Vérifie que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /* Traitement du formulaire si soumis */
        if ($request->isMethod('POST')) {
            /* Récupération des valeurs du formulaire */        
            $nouveauNiveau = $request->request->get('niveau_experience');
            $nouveauxPointsConnexion = $request->request->get('points_connexion');
            $nouveauxPointsConsultation = $request->request->get('points_consultation');
        
        /* Vérification et mise à jour des points de connexion */
        if (is_numeric($nouveauxPointsConnexion)) {
            $utilisateur->setPointsConnexion((float)$nouveauxPointsConnexion);
        }

        /* Vérification et mise à jour des points de consultation */
        if (is_numeric($nouveauxPointsConsultation)) {
            $utilisateur->setPointsConsultation((float)$nouveauxPointsConsultation);
        }
        
        /* Mise à jour du niveau en fonction des points */
        $pointsService->updateUserLevel($utilisateur);

        /* Enregistrement des modifications */
        $entityManager->flush();

        /* Message de confirmation pour l'administrateur */
        $this->addFlash('success', 'Utilisateur mis à jour avec succès !');
        return $this->redirectToRoute('admin');
    }

    return $this->render('admin/modifier_niveau_experience.html.twig', [
        'utilisateur' => $utilisateur,
    ]);
}

    /**
    * Ajoute un nouvel utilisateur dans le système
    * 
    * Cette méthode permet aux administrateurs de créer directement
    * un nouvel utilisateur avec tous ses attributs
    * 
    * @param Request $request Requête HTTP
    * @param EntityManagerInterface $entityManager Gestionnaire d'entités
    * @return Response La réponse HTTP
    */
   #[Route('/admin/utilisateur/ajouter', name: 'admin_utilisateur_ajouter')]
   public function ajouterUtilisateur(Request $request, EntityManagerInterface $entityManager): Response
   {
        /* Création d'une nouvelle instance d'utilisateur */   
        $utilisateur = new Utilisateur();  // Crée un nouvel utilisateur

        /* Création du formulaire pour l'ajout d'utilisateur */
        $form = $this->createFormBuilder($utilisateur)
            ->add('login')
            ->add('mot_de_passe', PasswordType::class)
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
            ]) 
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                    'Autre' => 'Autre',
                ],
            ]) 
            ->add('email')
            ->add('type_membre', TextType::class, [
                'label' => 'Type de membre',
            ])
            ->add('photo_url', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'mapped' => false,
                'attr' => ['accept' => 'image/*']
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter'])
            ->getForm();

        /* Traitement de la requête pour remplir le formulaire */
        $form->handleRequest($request);

        /* Vérification si le formulaire est soumis et valide */
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setMotDePasse(password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT));

            /* Définir la date d'inscription si elle n'est pas déjà définie */
            if ($utilisateur->getDateInscription() === null) {
                $utilisateur->setDateInscription(new \DateTime());  // Définit la date d'inscription si elle est nulle
        }

            /* Gérer l'upload de la photo si un fichier a été téléchargé */
            $photoFile = $form->get('photo_url')->getData();
            if ($photoFile) {
                /* Générer un nom de fichier unique */            
                $photoFilename = uniqid() . '.' . $photoFile->guessExtension();
                /* Déplacer le fichier dans le répertoire configuré */                
                $photoFile->move(
                    $this->getParameter('images_directory'),
                    $photoFilename
                );
                /* Définir le nom de la photo dans l'entité */                
                $utilisateur->setPhotoUrl($photoFilename);  
            }

            /* Persister l'utilisateur dans la base de données */
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté avec succès !');

            /* Redirection vers le tableau de bord */
            return $this->redirectToRoute('admin'); 
        }

        /* Affichage du formulaire d'ajout */
        return $this->render('admin/ajouter_utilisateur.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modifie le profil complet d'un utilisateur
     * 
     * Cette méthode permet aux administrateurs de modifier tous les
     * attributs d'un utilisateur existant, y compris son mot de passe
     * 
     * @param Utilisateur $utilisateur Utilisateur à modifier
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @param UserPasswordHasherInterface $passwordHasher Service de hachage de mot de passe
     * @return Response La réponse HTTP
     */
    #[Route('/admin/utilisateur/{id}/modifier-profil', name: 'admin_utilisateur_modifier_profil')]
    public function modifierProfil(
        Utilisateur $utilisateur, 
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /* Vérifie que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $motDePasseActuel = $utilisateur->getMotDePasse();
    
        /* Créer le formulaire de modification du profil */
        $form = $this->createFormBuilder($utilisateur)
            ->add('login', TextType::class)
            ->add('mot_de_passe', PasswordType::class, [
                'required' => false,
                'mapped' => true,
                'attr' => ['placeholder' => 'Entrez un nouveau mot de passe si vous souhaitez le modifier']
            ])
            ->add('nom', TextType::class, ['required' => false])
            ->add('prenom', TextType::class, ['required' => false])
            ->add('email', EmailType::class)
            ->add('type_utilisateur', ChoiceType::class, [
                'choices' => [
                    'Visiteur' => 'visiteur',
                    'Administrateur' => 'administrateur'
                ]
            ])
            ->add('type_membre', TextType::class, ['required' => false])
            ->add('photo_url', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'mapped' => false,
                'attr' => ['accept' => 'image/*']
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer les modifications'])
            ->getForm();
    
        $form->handleRequest($request);

        /* Vérification si le formulaire est soumis et valide */
        if ($form->isSubmitted() && $form->isValid()) {
            /* Vérifier si le mot de passe a été modifié */
            $motDePasseSaisi = $utilisateur->getMotDePasse();
        
            /* 
             * Si le mot de passe saisi est différent du mot de passe haché stocké,
             * c'est qu'il a été modifié dans le formulaire, donc on le hache
             */
             if ($motDePasseSaisi !== $motDePasseActuel) {
                 $hashedPassword = $passwordHasher->hashPassword($utilisateur, $motDePasseSaisi);
                 $utilisateur->setMotDePasse($hashedPassword);
            } else {
             $utilisateur->setMotDePasse($motDePasseActuel);
            }
        
            $photoFile = $form->get('photo_url')->getData();
        
            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
            
                try {
                    $photoFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                
                    $utilisateur->setPhotoUrl($newFilename);
                
                } catch (FileException $e) {
                    $this->addFlash('error', "Une erreur est survenue lors de l'upload de l'image.");
                }
            }
        
            $entityManager->flush();
            $this->addFlash('success', 'Profil de l\'utilisateur mis à jour avec succès !');
            return $this->redirectToRoute('admin');
        }
    
        return $this->render('admin/modifier_profil.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche les statistiques d'utilisation du système
     * 
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @return Response La réponse HTTP
     */
    #[Route('/admin/statistiques', name: 'admin_statistiques')]
    public function statistiques(EntityManagerInterface $entityManager): Response
    {
        /* Vérifie que seuls les administrateurs peuvent accéder à cette page */
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /* Calculer la date de début de la semaine dernière (7 jours en arrière) */
        $dateSeuil = new \DateTime();
        $dateSeuil->modify('-7 days');

        /* Récupérer le nombre total de connexions */
        $totalConnexions = $entityManager->createQueryBuilder()
            ->select('COUNT(h.id)')
            ->from(HistoriqueConnexion::class, 'h')
            ->getQuery()
            ->getSingleScalarResult();

        /* Récupérer les connexions par utilisateur et leur nombre total */
         $connexions = $entityManager->createQueryBuilder()
            ->select('u.id, u.login, u.email, u.nom, u.prenom, COUNT(h.id) AS nb_connexions')
            ->from(HistoriqueConnexion::class, 'h')
            ->join('h.utilisateur', 'u')
            ->groupBy('u.id')
            ->getQuery()
            ->getResult();

        /* Récupérer les connexions de la dernière semaine */
        $connexionsSemaine = $entityManager->createQueryBuilder()
            ->select('u.id, COUNT(h.id) AS connexions_semaine')
            ->from(HistoriqueConnexion::class, 'h')
            ->join('h.utilisateur', 'u')
            ->where('h.dateConnexion >= :dateSeuil')
            ->setParameter('dateSeuil', $dateSeuil)
            ->groupBy('u.id')
            ->getQuery()
            ->getResult();

        /* Organiser les données par utilisateur */
        foreach ($connexions as &$connexion) {
            $connexion['connexions_semaine'] = 0; // Initialiser à 0
            $connexion['pourcentage_connexions'] = 0;

            /* Trouver le nombre de connexions dans la dernière semaine */
            foreach ($connexionsSemaine as $connexionSemaine) {
                if ($connexionSemaine['id'] === $connexion['id']) {
                    $connexion['connexions_semaine'] = $connexionSemaine['connexions_semaine'];
                    break;
                }
            }

            /* Calculer le pourcentage de connexions par rapport au total */
            $connexion['pourcentage_connexions'] = ($totalConnexions > 0) ? ($connexion['nb_connexions'] / $totalConnexions) * 100 : 0;
        }

        return $this->render('admin/statistiques.html.twig', [
            'connexions' => $connexions, // Passer la variable correctement
            'total_connexions' => $totalConnexions,
        ]);
    }

    /**
     * Affiche l'historique des consultations avec filtrage
     * 
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @return Response La réponse HTTP
     */
    #[Route('/admin/historique-consultation', name: 'admin_historique_consultation')]
    public function historiqueConsultations(Request $request, EntityManagerInterface $entityManager): Response
    {
        /* Vérifie que seuls les administrateurs peuvent accéder à cette page */    
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /* Récupérer tous les utilisateurs pour le filtrage */
        $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();
    
        /* Récupérer les filtres de la requête */
        $userId = $request->query->get('utilisateur');
        $typeElement = $request->query->get('type');
    
        /* Construire les critères de recherche selon les filtres */
        $criteria = [];
        if ($userId) {
            $criteria['utilisateur'] = $userId;
        }
        if ($typeElement) {
            $criteria['typeElement'] = $typeElement;
        }
    
        /* 
         * Récupérer les consultations avec tri par date 
         * (les plus récentes d'abord)
         */
        $consultations = $entityManager->getRepository(HistoriqueConsultation::class)
            ->findBy($criteria, ['dateConsultation' => 'DESC']);

        return $this->render('admin/historique_consultation.html.twig', [
            'consultations' => $consultations,
            'utilisateurs' => $utilisateurs,
            'selectedUser' => $userId,
            'selectedType' => $typeElement
        ]);
    }

    /**
     * Affiche la liste des utilisateurs en attente de validation
     * 
     * @param UtilisateurRepository $userRepository Repository des utilisateurs
     * @return Response La réponse HTTP
     */
    #[Route('/utilisateurs/en-attente', name: 'admin_users_pending')]
    public function pendingUsers(UtilisateurRepository $userRepository): Response
    {
        /* Récupérer tous les utilisateurs en attente de vérification */    
        $users = $userRepository->findBy(['statut_verification' => 'en_attente']);

        /* Affichage de la liste des utilisateurs en attente */
        return $this->render('admin/pending_users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Vérifie et approuve ou rejette un compte utilisateur en attente
     * 
     * @param Utilisateur $user Utilisateur à vérifier
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $em Gestionnaire d'entités
     * @param MailerInterface $mailer Service d'envoi d'emails
     * @return Response La réponse HTTP
     */
    #[Route('/utilisateur/verifier/{id}', name: 'admin_verify_user')]
    public function verifyUser(
        Utilisateur $user, 
        Request $request, 
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        /* Afficher les détails pour vérification si c'est une requête GET */
        if ($request->isMethod('GET')) {
            return $this->render('admin/verify_user.html.twig', [
                'user' => $user,
            ]);
        }
        
        /* Récupérer la décision et le message du formulaire */
        $decision = $request->request->get('decision');
        $message = $request->request->get('message');

        /* Si la décision est d'approuver l'utilisateur */
        if ($decision === 'approve') {
            /* Mettre à jour le statut et valider le compte */        
            $user->setStatutVerification('approuve');
            $user->setCompteValide(true);
            $this->sendApprovalEmail($user, $mailer);
            $this->addFlash('success', 'Utilisateur approuvé avec succès');
        } else {
            /* Si la décision est de rejeter l'utilisateur */        
            $user->setStatutVerification('rejete');
            $user->setIsConfirmed(false);
            
            /* Envoyer l'email de rejet avec le message explicatif */            
            $this->sendRejectionEmail($user, $message, $mailer);
            $this->addFlash('warning', 'Utilisateur rejeté');
        }

        /* Enregistrer les modifications dans la base de données */
        $em->flush();

        /* Redirection vers la liste des utilisateurs en attente */
        return $this->redirectToRoute('admin_users_pending');
    }


    /**
     * Envoie un email d'approbation à l'utilisateur
     * 
     * @param Utilisateur $user Utilisateur à notifier
     * @param MailerInterface $mailer Service d'envoi d'emails
     * @return void
     */
    private function sendApprovalEmail(Utilisateur $user, MailerInterface $mailer): void
    {
        /* 
         * Création de l'email avec template Twig 
         * et informations de l'utilisateur
         */    
        $email = (new TemplatedEmail())
            ->from('valmontcitynoreply@gmail.com')
            ->to($user->getEmail())
            ->subject('Votre compte Valmont a été approuvé')
            ->htmlTemplate('admin/approval_email.html.twig')
            ->context([
                'user' => $user,
            ]);
        /* Envoi de l'email */        
        $mailer->send($email);
    }

    /**
     * Envoie un email de rejet à l'utilisateur
     * 
     * @param Utilisateur $user Utilisateur à notifier
     * @param string $message Message explicatif du rejet
     * @param MailerInterface $mailer Service d'envoi d'emails
     * @return void
     */
    private function sendRejectionEmail(Utilisateur $user, string $message, MailerInterface $mailer): void
    {
        /* 
         * Création de l'email avec template Twig, 
         * informations de l'utilisateur et message personnalisé
         */    
        $email = (new TemplatedEmail())
            ->from('valmontcitynoreply@gmail.com')
            ->to($user->getEmail())
            ->subject('Information concernant votre inscription à Valmont')
            ->htmlTemplate('admin/rejection_email.html.twig')
            ->context([
                'user' => $user,
                'message' => $message
            ]);

        /* 
         * Création de l'email avec template Twig, 
         * informations de l'utilisateur et message personnalisé
         */
        $mailer->send($email);
    }
}
