<?php

// src/Form/AppearanceConfigType.php
namespace App\Form;

use App\Entity\AppearanceConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppearanceConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('themeName', ChoiceType::class, [
                'label' => 'Thème',
                'choices' => [
                    'Thème par défaut' => 'default',
                    'Thème personnalisé' => 'dark',
                ]
            ])
            ->add('primaryColor', ColorType::class, [
                'label' => 'Couleur principale'
            ])
            ->add('secondaryColor', ColorType::class, [
                'label' => 'Couleur secondaire'
            ])
            ->add('informationModuleEnabled', CheckboxType::class, [
                'label' => 'Afficher le module Information',
                'mapped' => false,
                'data' => $options['data']->getModuleLayout()['information']['enabled'] ?? true,
                'required' => false
            ])
            ->add('informationModuleOrder', NumberType::class, [
                'label' => 'Ordre d\'affichage',
                'mapped' => false,
                'data' => $options['data']->getModuleLayout()['information']['order'] ?? 1,
                'attr' => ['min' => 1, 'max' => 4]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppearanceConfig::class,
        ]);
    }
}
