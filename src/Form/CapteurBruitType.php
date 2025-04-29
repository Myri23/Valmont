<?php

namespace App\Form;

// Importation des entités nécessaires
use App\Entity\CapteurBruit;
use App\Entity\ObjetConnecte;

// Importation des types de formulaire Symfony
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la gestion des capteurs de bruit
 * Cette classe permet de créer et modifier les capteurs de bruit
 */
class CapteurBruitType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Constructeur de formulaire
     * @param array $options Options du formulaire
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
            // Champ pour le niveau de décibels mesuré
            ->add('niveau_decibel', NumberType::class, [
                'label' => 'Niveau de décibel',
                'required' => false,
                'attr' => [
                    'step' => '0.1' // Permet des valeurs décimales
                ]
            ])
            // Seuil d'alerte en décibels
            ->add('seuil_alerte', NumberType::class, [
                'label' => 'Seuil d\'alerte (dB)',
                'required' => false,
                'attr' => [
                    'step' => '0.1' // Permet des valeurs décimales
                ]
            ])
            // Date et heure de la dernière alerte
            ->add('derniere_alerte', DateTimeType::class, [
                'label' => 'Dernière alerte',
                'required' => false,
                'widget' => 'single_text', // Affichage en un seul champ
            ])
            // Bouton de soumission du formulaire
            ->add('saveCapteurBruit', SubmitType::class, [
                'label' => 'Ajouter le capteur de bruit'
            ]);
    }

    /**
     * Configure les options par défaut du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CapteurBruit::class, // Liaison avec l'entité CapteurBruit
        ]);
    }
}
