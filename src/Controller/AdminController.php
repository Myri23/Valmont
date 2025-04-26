<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\HistoriqueConnexion;  // Assure-toi que c'est bien ce namespace
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

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
        // Vérifie que seuls les administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Vérifier si l'utilisateur essaie de se supprimer lui-même
        if ($utilisateur === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte !');
            return $this->redirectToRoute('admin');
        }
        
        // Supprime l'utilisateur
        $entityManager->remove($utilisateur);
        $entityManager->flush();
        
        // Ajoute un message flash pour confirmer la suppression
        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
        
        // Redirige vers la page d'administration
        return $this->redirectToRoute('admin');
    }
    
    #[Route('/admin/historique', name: 'admin_historique_connexion')]
    public function historiqueConnexions(EntityManagerInterface $entityManager): Response
    {
        // Utiliser le repository de l'entité HistoriqueConnexion
        $connexions = $entityManager->getRepository(HistoriqueConnexion::class)->findAll();

        return $this->render('admin/historique_connexion.html.twig', [
            'connexions' => $connexions,
        ]);
    }
    
#[Route('/admin/utilisateur/{id}/modifier', name: 'admin_utilisateur_modifier')]
public function modifierNiveauExperience(Utilisateur $utilisateur, Request $request, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if ($request->isMethod('POST')) {
        $nouveauNiveau = $request->request->get('niveau_experience');
        $utilisateur->setNiveauExperience($nouveauNiveau);
        $entityManager->flush();

        $this->addFlash('success', 'Niveau d\'expérience mis à jour !');
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



}
