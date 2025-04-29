<?php

namespace App\Form;

use App\Entity\AppearanceConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de configuration de l'apparence de l'application
 * Permet de personnaliser les thèmes et les modules d'affichage
 */
class AppearanceConfigType extends AbstractType
{
    /**
     * Construction du formulaire de configuration d'apparence
     * 
     * @param FormBuilderInterface $builder Constructeur de formulaire Symfony
     * @param array $options Options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Sélection du thème via une liste déroulante
            ->add('themeName', ChoiceType::class, [
                'label' => 'Thème',
                'choices' => [
                    'Thème par défaut' => 'default',
                    'Thème personnalisé' => 'dark',
                ]
            ])
            // Sélecteur de couleur principale
            ->add('primaryColor', ColorType::class, [
                'label' => 'Couleur principale'
            ])
            // Sélecteur de couleur secondaire
            ->add('secondaryColor', ColorType::class, [
                'label' => 'Couleur secondaire'
            ])
            // Activation/désactivation du module d'information
            ->add('informationModuleEnabled', CheckboxType::class, [
                'label' => 'Afficher le module Information',
                'mapped' => false, // Non mappé directement à l'entité
                'data' => $options['data']->getModuleLayout()['information']['enabled'] ?? true,
                'required' => false
            ])
            // Définition de l'ordre d'affichage du module
            ->add('informationModuleOrder', NumberType::class, [
                'label' => 'Ordre d\'affichage',
                'mapped' => false, // Non mappé directement à l'entité
                'data' => $options['data']->getModuleLayout()['information']['order'] ?? 1,
                'attr' => ['min' => 1, 'max' => 4] // Limite les valeurs possibles entre 1 et 4
            ])
        ;
    }

    /**
     * Configure les options par défaut du formulaire
     * 
     * @param OptionsResolver $resolver Résolveur d'options Symfony
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppearanceConfig::class, // Lie le formulaire à l'entité AppearanceConfig
        ]);
    }
}