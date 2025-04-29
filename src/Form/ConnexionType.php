<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// Importation des composants Symfony nécessaires
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Formulaire de connexion des utilisateurs
 * Gère l'authentification des utilisateurs dans l'application
 */
class ConnexionType extends AbstractType
{
    /**
     * Construction du formulaire de connexion
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ pour l'identifiant de connexion
            ->add('login', TextType::class)
            // Champ pour le mot de passe (masqué)
            ->add('mot_de_passe', PasswordType::class);
    }

    /**
     * Configuration des options du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
