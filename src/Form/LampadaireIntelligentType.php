<?php

namespace App\Form;

// Importation des classes nécessaires
use App\Entity\LampadaireIntelligent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de gestion des lampadaires intelligents
 * Cette classe permet de configurer les paramètres d'éclairage automatique
 */
class LampadaireIntelligentType extends AbstractType
{
    /**
     * Construction du formulaire avec les paramètres d'éclairage
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Configuration de l'heure d'allumage automatique
            ->add('heureAllumage', TimeType::class, [
                'label' => 'Heure d\'allumage',
                'widget' => 'single_text',
                'required' => false,
            ])
            // Définition de la durée d'éclairage
            ->add('dureeAllumage', IntegerType::class, [
                'label' => 'Durée d\'allumage (minutes)',
                'attr' => [
                    'min' => 0,
                    'max' => 1440 // 24 heures en minutes
                ]
            ]);
    }

    /**
     * Configuration des options par défaut du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LampadaireIntelligent::class, // Liaison avec l'entité LampadaireIntelligent
        ]);
    }
}
