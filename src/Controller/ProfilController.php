<?php
// src/Controller/ProfilController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ModifierProfilType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilController extends AbstractController
{
    #[Route('/modifier_profil', name: 'modifier_profil')]
    public function modifier_profil(
        Request $request, 
        EntityManagerInterface $entityManager, 
        SluggerInterface $slugger, 
        UserPasswordHasherInterface $passwordHasher
    ): Response 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }
        
        // Stocker le mot de passe actuel haché
        $motDePasseActuel = $user->getMotDePasse();
        
        $form = $this->createForm(ModifierProfilType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe actuel saisi et le nouveau mot de passe
            $motDePasseActuelSaisi = $form->get('mot_de_passe_actuel')->getData();
            $nouveauMotDePasse = $form->get('nouveau_mot_de_passe')->getData();
            
            // Vérifier si l'utilisateur souhaite changer son mot de passe
            if ($nouveauMotDePasse) {
                // Vérifier si le mot de passe actuel saisi est correct
                if (!$passwordHasher->isPasswordValid($user, $motDePasseActuelSaisi)) {
                    $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                    return $this->render('home/modifier_profil.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                
                // Si le mot de passe actuel est correct, hasher le nouveau mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $nouveauMotDePasse);
                $user->setMotDePasse($hashedPassword);
            } else {
                // Si l'utilisateur ne change pas de mot de passe, conserver l'ancien
                $user->setMotDePasse($motDePasseActuel);
            }
            
            // Gérer l'upload de la photo si nécessaire
            $photoFile = $form->get('photo_url')->getData();
            
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Sécuriser le nom du fichier
                $safeFilename = $slugger->slug($originalFilename);
                // Générer un nom unique pour éviter les collisions
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();
                
                try {
                    // Déplacer le fichier dans le répertoire où sont stockées les images
                    $photoFile->move(
                        $this->getParameter('images_directory'), // Défini dans services.yaml
                        $newFilename
                    );
                    
                    // Mettre à jour l'utilisateur avec le nouveau nom de fichier
                    $user->setPhotoUrl($newFilename);
                    
                } catch (FileException $e) {
                    // Gérer l'erreur si l'upload échoue
                    $this->addFlash('error', "Une erreur est survenue lors de l'upload de l'image.");
                }
            }
            
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('home');
        }
        
        return $this->render('visualisation/modifier_profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/profil', name: 'profil')]
    public function profil(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }
        
        return $this->render('visualisation/profil.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/membres', name: 'membres')]
    public function membres(EntityManagerInterface $entityManager): Response
    {
        
        // Récupère tous les utilisateurs depuis la base de données
        $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();
        
        return $this->render('visualisation/membres.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}
