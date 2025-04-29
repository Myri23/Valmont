<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// Importation des classes nécessaires
use App\Entity\CameraSurveillance;
use App\Entity\ObjetConnecte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la gestion des caméras de surveillance
 * Cette classe permet de créer et modifier les caméras de surveillance
 */
class CameraSurveillanceType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Association avec un objet connecté
            ->add('objet', EntityType::class, [
                'class' => ObjetConnecte::class,
                'choice_label' => 'nom',
                'label' => 'Objet connecté lié',
            ])
            // Définition de la résolution de la caméra
            ->add('resolution', TextType::class, [
                'required' => false,
            ])
            // Activation/désactivation de la détection de mouvement
            ->add('detection_mouvement', CheckboxType::class, [
                'required' => false,
                'label' => 'Détection de mouvement ?',
            ])
            // Activation/désactivation de la vision nocturne
            ->add('vision_nocturne', CheckboxType::class, [
                'required' => false,
                'label' => 'Vision nocturne ?',
            ])
            // Sélection de l'angle de vision de la caméra
            ->add('angle_vision', ChoiceType::class, [
                'required' => false,
                'label' => 'Angle de vision',
                'choices' => [
                    '90°' => '90°',
                    '120°' => '120°',
                    '180°' => '180°',
                    '360°' => '360°',
                ]
            ])
            // Bouton de soumission du formulaire
            ->add('saveCamera', SubmitType::class, [
                'label' => 'Ajouter la caméra',
            ]);
    }

    /**
     * Configuration des options du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CameraSurveillance::class, // Liaison avec l'entité CameraSurveillance
        ]);
    }
}
