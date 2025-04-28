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
use App\Entity\HistoriqueConsultation;  // Ajoutez cet import ici
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


final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Vérifie que seuls les administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Récupère tous les utilisateurs depuis la base de données
        $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();
        
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'utilisateurs' => $utilisateurs,
        ]);
    }
    
#[Route('/admin/utilisateur/{id}/supprimer', name: 'admin_utilisateur_supprimer')]
public function supprimer(Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if ($utilisateur === $this->getUser()) {
        $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte !');
        return $this->redirectToRoute('admin');
    }

    // 👉 Supprimer d'abord les historiques de connexion liés à cet utilisateur
    $connexions = $entityManager->getRepository(\App\Entity\HistoriqueConnexion::class)->findBy(['utilisateur' => $utilisateur]);
    foreach ($connexions as $connexion) {
        $entityManager->remove($connexion);
    }

    // Ensuite supprimer l'utilisateur
    $entityManager->remove($utilisateur);
    $entityManager->flush();

    $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

    return $this->redirectToRoute('admin');
}

    
#[Route('/admin/historique', name: 'admin_historique_connexion')]
public function historiqueConnexions(Request $request, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // Récupérer tous les utilisateurs pour le filtrage
    $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();
    
    // Récupérer le filtre utilisateur
    $userId = $request->query->get('utilisateur');
    
    // Construire les critères de recherche
    $criteria = [];
    if ($userId) {
        $criteria['utilisateur'] = $userId;
    }
    
    // Récupérer les connexions avec tri par date (les plus récentes d'abord)
    $connexions = $entityManager->getRepository(HistoriqueConnexion::class)
        ->findBy($criteria, ['dateConnexion' => 'DESC']);

    return $this->render('admin/historique_connexion.html.twig', [
        'connexions' => $connexions,
        'utilisateurs' => $utilisateurs,
        'selectedUser' => $userId
    ]);
}

#[Route('/admin/utilisateur/{id}/modifier', name: 'admin_utilisateur_modifier')]
public function modifierNiveauExperience(
    Utilisateur $utilisateur, 
    Request $request, 
    EntityManagerInterface $entityManager,
    PointsService $pointsService
): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if ($request->isMethod('POST')) {
        $nouveauNiveau = $request->request->get('niveau_experience');
        $nouveauxPointsConnexion = $request->request->get('points_connexion');
        $nouveauxPointsConsultation = $request->request->get('points_consultation');
        
        // Vérifier que les valeurs sont numériques
        if (is_numeric($nouveauxPointsConnexion)) {
            $utilisateur->setPointsConnexion((float)$nouveauxPointsConnexion);
        }
        
        if (is_numeric($nouveauxPointsConsultation)) {
            $utilisateur->setPointsConsultation((float)$nouveauxPointsConsultation);
        }
        
        // Mettre à jour le niveau en fonction des points
        $pointsService->updateUserLevel($utilisateur);
        
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur mis à jour avec succès !');
        return $this->redirectToRoute('admin');
    }

    return $this->render('admin/modifier_niveau_experience.html.twig', [
        'utilisateur' => $utilisateur,
    ]);
}

#[Route('/admin/utilisateur/ajouter', name: 'admin_utilisateur_ajouter')]
public function ajouterUtilisateur(Request $request, EntityManagerInterface $entityManager): Response
{
    $utilisateur = new Utilisateur();  // Crée un nouvel utilisateur

    // Crée le formulaire pour l'ajout d'utilisateur
    $form = $this->createFormBuilder($utilisateur)
        ->add('login') // Champ pour le login
        ->add('mot_de_passe', PasswordType::class) // Champ pour le mot de passe
        ->add('nom') // Champ pour le nom
        ->add('prenom') // Champ pour le prénom
        ->add('date_naissance', DateType::class, [
            'widget' => 'single_text', // Pour afficher un champ de date au format texte
        ]) // Champ pour la date de naissance
        ->add('sexe', ChoiceType::class, [
            'choices' => [
                'Homme' => 'Homme',
                'Femme' => 'Femme',
                'Autre' => 'Autre',
            ],
        ]) // Champ pour le sexe
        ->add('email') // Champ pour l'email
   ->add('type_membre', TextType::class, [
        'label' => 'Type de membre', // Si tu veux afficher une étiquette personnalisée
        ])
        ->add('photo_url', FileType::class, [
            'label' => 'Photo de profil',
            'required' => false,
            'mapped' => false,
            'attr' => ['accept' => 'image/*']
        ]) // Champ pour l'URL de la photo
        ->add('save', SubmitType::class, ['label' => 'Ajouter'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hash le mot de passe avant de le sauvegarder
        $utilisateur->setMotDePasse(password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT));

        // Assure-toi que la date d'inscription est bien définie avant de persister l'utilisateur
        if ($utilisateur->getDateInscription() === null) {
            $utilisateur->setDateInscription(new \DateTime());  // Définit la date d'inscription si elle est nulle
        }

        // Gérer l'upload de la photo (si un fichier a été téléchargé)
        $photoFile = $form->get('photo_url')->getData();
        if ($photoFile) {
            $photoFilename = uniqid() . '.' . $photoFile->guessExtension();
            $photoFile->move(
                $this->getParameter('images_directory'),
                $photoFilename
            );
            $utilisateur->setPhotoUrl($photoFilename);  // Définit le nom de la photo
        }

        // Persiste l'utilisateur dans la base de données
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur ajouté avec succès !');

        return $this->redirectToRoute('admin'); // Redirige vers la liste des utilisateurs
    }

    return $this->render('admin/ajouter_utilisateur.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/admin/utilisateur/{id}/modifier-profil', name: 'admin_utilisateur_modifier_profil')]
public function modifierProfil(
    Utilisateur $utilisateur, 
    Request $request, 
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
): Response {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
    // Stocker le mot de passe actuel haché
    $motDePasseActuel = $utilisateur->getMotDePasse();
    
    // Créer le formulaire
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
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier si le mot de passe a été modifié
        $motDePasseSaisi = $utilisateur->getMotDePasse();
        
        // Si le mot de passe saisi est différent du mot de passe haché stocké en base,
        // c'est qu'il a été modifié dans le formulaire, donc on le hache
        if ($motDePasseSaisi !== $motDePasseActuel) {
            // Hacher le nouveau mot de passe
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $motDePasseSaisi);
            $utilisateur->setMotDePasse($hashedPassword);
        } else {
            // Si identique, on restaure l'ancien mot de passe haché
            $utilisateur->setMotDePasse($motDePasseActuel);
        }
        
        // Gérer l'upload de la photo si nécessaire
        $photoFile = $form->get('photo_url')->getData();
        
        if ($photoFile) {
            $newFilename = uniqid() . '.' . $photoFile->guessExtension();
            
            try {
                // Déplacer le fichier
                $photoFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                
                // Mettre à jour la photo
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

#[Route('/admin/statistiques', name: 'admin_statistiques')]
public function statistiques(EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // Calculer la date de début de la semaine dernière (7 jours en arrière)
    $dateSeuil = new \DateTime();
    $dateSeuil->modify('-7 days');  // Cela retourne la date de 7 jours avant aujourd'hui

    // Récupérer le nombre total de connexions
    $totalConnexions = $entityManager->createQueryBuilder()
        ->select('COUNT(h.id)')
        ->from(HistoriqueConnexion::class, 'h')
        ->getQuery()
        ->getSingleScalarResult();

    // Récupérer les connexions par utilisateur et leur nombre total de connexions
    $connexions = $entityManager->createQueryBuilder()
        ->select('u.id, u.login, u.email, u.nom, u.prenom, COUNT(h.id) AS nb_connexions')
        ->from(HistoriqueConnexion::class, 'h')
        ->join('h.utilisateur', 'u')
        ->groupBy('u.id')
        ->getQuery()
        ->getResult();

    // Récupérer les connexions de la dernière semaine
    $connexionsSemaine = $entityManager->createQueryBuilder()
        ->select('u.id, COUNT(h.id) AS connexions_semaine')
        ->from(HistoriqueConnexion::class, 'h')
        ->join('h.utilisateur', 'u')
        ->where('h.dateConnexion >= :dateSeuil')
        ->setParameter('dateSeuil', $dateSeuil)
        ->groupBy('u.id')
        ->getQuery()
        ->getResult();

    // Organiser les données par utilisateur
    foreach ($connexions as &$connexion) {
        $connexion['connexions_semaine'] = 0; // Initialiser à 0
        $connexion['pourcentage_connexions'] = 0;

        // Trouver le nombre de connexions dans la dernière semaine
        foreach ($connexionsSemaine as $connexionSemaine) {
            if ($connexionSemaine['id'] === $connexion['id']) {
                $connexion['connexions_semaine'] = $connexionSemaine['connexions_semaine'];
                break;
            }
        }

        // Calculer le pourcentage de connexions
        $connexion['pourcentage_connexions'] = ($totalConnexions > 0) ? ($connexion['nb_connexions'] / $totalConnexions) * 100 : 0;
    }

    // Passer les données à la vue
    return $this->render('admin/statistiques.html.twig', [
        'connexions' => $connexions, // Passer la variable correctement
        'total_connexions' => $totalConnexions,
    ]);
}
#[Route('/admin/historique-consultation', name: 'admin_historique_consultation')]
public function historiqueConsultations(Request $request, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // Récupérer tous les utilisateurs pour le filtrage
    $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();
    
    // Récupérer les filtres
    $userId = $request->query->get('utilisateur');
    $typeElement = $request->query->get('type');
    
    // Construire les critères de recherche
    $criteria = [];
    if ($userId) {
        $criteria['utilisateur'] = $userId;
    }
    if ($typeElement) {
        $criteria['typeElement'] = $typeElement;
    }
    
    // Récupérer les consultations avec tri par date (les plus récentes d'abord)
    $consultations = $entityManager->getRepository(HistoriqueConsultation::class)
        ->findBy($criteria, ['dateConsultation' => 'DESC']);

    return $this->render('admin/historique_consultation.html.twig', [
        'consultations' => $consultations,
        'utilisateurs' => $utilisateurs,
        'selectedUser' => $userId,
        'selectedType' => $typeElement
    ]);
}

    #[Route('/utilisateurs/en-attente', name: 'admin_users_pending')]
    public function pendingUsers(UtilisateurRepository $userRepository): Response
    {
        $users = $userRepository->findBy(['statut_verification' => 'en_attente']);
        
        return $this->render('admin/pending_users.html.twig', [
            'users' => $users,
        ]);
    }
    
    #[Route('/utilisateur/verifier/{id}', name: 'admin_verify_user')]
    public function verifyUser(
        Utilisateur $user, 
        Request $request, 
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        // Afficher les détails pour vérification
        if ($request->isMethod('GET')) {
            return $this->render('admin/verify_user.html.twig', [
                'user' => $user,
            ]);
        }
        
        // Traiter la décision
        $decision = $request->request->get('decision');
        $message = $request->request->get('message');
        
        if ($decision === 'approve') {
            $user->setStatutVerification('approuve');
            $user->setCompteValide(true);
            $this->sendApprovalEmail($user, $mailer);
            $this->addFlash('success', 'Utilisateur approuvé avec succès');
        } else {
            $user->setStatutVerification('rejete');
            $user->setIsConfirmed(false);
            $this->sendRejectionEmail($user, $message, $mailer);
            $this->addFlash('warning', 'Utilisateur rejeté');
        }
        
        $em->flush();
        
        return $this->redirectToRoute('admin_users_pending');
    }
    
    private function sendApprovalEmail(Utilisateur $user, MailerInterface $mailer): void
    {
        $email = (new TemplatedEmail())
            ->from('valmontcitynoreply@gmail.com')
            ->to($user->getEmail())
            ->subject('Votre compte Valmont a été approuvé')
            ->htmlTemplate('admin/approval_email.html.twig')
            ->context([
                'user' => $user,
            ]);
        
        $mailer->send($email);
    }
    
    private function sendRejectionEmail(Utilisateur $user, string $message, MailerInterface $mailer): void
    {
        $email = (new TemplatedEmail())
            ->from('valmontcitynoreply@gmail.com')
            ->to($user->getEmail())
            ->subject('Information concernant votre inscription à Valmont')
            ->htmlTemplate('admin/rejection_email.html.twig')
            ->context([
                'user' => $user,
                'message' => $message
            ]);
        
        $mailer->send($email);
    }


}