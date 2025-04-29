<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// Importation des entités et composants nécessaires
use App\Entity\FeuCirculation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Formulaire de gestion des feux de circulation
 * Permet de configurer les paramètres de fonctionnement des feux
 */
class FeuCirculationType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Association avec un objet connecté
            ->add('objet')
            // Sélection de l'état actuel du feu
            ->add('etatActuel', ChoiceType::class, [
                'choices' => [
                    'Vert' => 'vert',
                    'Orange' => 'orange',
                    'Rouge' => 'rouge',
                    'Clignotant' => 'clignotant',
                    'Éteint' => 'eteint',
                ],
            ])
            // Mode de fonctionnement du feu
            ->add('modeFonctionnement', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'normal',
                    'Adaptatif' => 'adaptatif',
                    'Clignotant' => 'clignotant',
                    'Éteint' => 'eteint',
                ],
            ])
            // Durée du cycle complet du feu
            ->add('dureeCycle')
            // Gestion de la priorité pour les transports en commun
            ->add('prioriteTransportCommun')
            // Activation de la détection automatique des véhicules
            ->add('detectionVehicules')
        ;
    }

    /**
     * Configuration des options du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FeuCirculation::class, // Liaison avec l'entité FeuCirculation
        ]);
    }
}
