<?php

namespace App\Form;

// Importation des types de formulaire nécessaires
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Formulaire de gestion des utilisateurs
 * Permet de créer et modifier les comptes utilisateurs
 */
class UtilisateurType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Identifiant de connexion
            ->add('login', TextType::class)
            // Mot de passe de l'utilisateur
            ->add('mot_de_passe', PasswordType::class)
            // Nom de famille de l'utilisateur
            ->add('nom', TextType::class, [
                'required' => false,
            ])
            // Prénom de l'utilisateur
            ->add('prenom', TextType::class, [
                'required' => false,
            ])
            // Date de naissance de l'utilisateur
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            // Genre de l'utilisateur
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
                'required' => false,
            ])
            // Adresse email de l'utilisateur
            ->add('email', EmailType::class)
            // Type de compte utilisateur
            ->add('type_membre', TextType::class, [
                'required' => false,
            ])
            // Photo de profil de l'utilisateur
            ->add('photo_url', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    /**
     * Configure les options par défaut du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class, // Liaison avec l'entité Utilisateur
        ]);
    }
}
