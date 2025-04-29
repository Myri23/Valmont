<?php

namespace App\Form;

// Importation des classes nécessaires
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Formulaire de modification du profil utilisateur
 * Permet aux utilisateurs de mettre à jour leurs informations personnelles
 */
class ModifierProfilType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ pour modifier l'identifiant
            ->add('login', TextType::class, [
                'label' => 'Identifiant',
                'attr' => ['class' => 'form-control']
            ])
            // Vérification du mot de passe actuel
            ->add('mot_de_passe_actuel', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            // Champ pour le nouveau mot de passe
            ->add('nouveau_mot_de_passe', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Laissez vide si vous ne souhaitez pas modifier votre mot de passe'
                ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            // Champ pour le nom de l'utilisateur
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            // Champ pour le prénom de l'utilisateur
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            // Champ pour l'adresse email
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control']
            ])
            // Upload de la photo de profil
            ->add('photo_url', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control-file']
            ])
        ;
    }

    /**
     * Configuration des options du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class, // Liaison avec l'entité Utilisateur
        ]);
    }
}
