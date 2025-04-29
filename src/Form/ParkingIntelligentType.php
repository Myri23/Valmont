<?php

namespace App\Form;

// Importation des classes nécessaires
use App\Entity\ParkingIntelligent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de gestion des parkings intelligents
 * Cette classe permet de configurer les informations des parkings connectés
 */
class ParkingIntelligentType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Définition du nombre total de places dans le parking
            ->add('places_totales', IntegerType::class, [
                'label' => 'Nombre total de places',
                'attr' => [
                    'min' => 1
                ]
            ])
            // Nombre de places actuellement disponibles
            ->add('places_disponibles', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => [
                    'min' => 0
                ]
            ])
            // Emplacement détaillé du parking
            ->add('localisation_precise', TextType::class, [
                'label' => 'Localisation précise'
            ]);
    }

    /**
     * Configuration des options par défaut du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParkingIntelligent::class, // Liaison avec l'entité ParkingIntelligent
        ]);
    }
}
