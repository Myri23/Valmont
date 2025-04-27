<?php

namespace App\Form;

use App\Entity\PoubelleConnectee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoubelleConnecteeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('niveauRemplissage', IntegerType::class, [
                'label' => 'Niveau de remplissage actuel (%)',
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1
                ]
            ])
            ->add('capaciteTotale', IntegerType::class, [
                'label' => 'Capacité totale (litres)',
            ])
            ->add('typeDechets', ChoiceType::class, [
                'label' => 'Type de déchets',
                'choices' => [
                    'Ordures ménagères' => 'ordures',
                    'Recyclable' => 'recyclable',
                    'Verre' => 'verre',
                    'Compost' => 'compost',
                    'Mixte' => 'mixte',
                ],
            ])
            ->add('derniereCollecte', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Dernière collecte',
            ])
            ->add('compacteur', CheckboxType::class, [
                'required' => false,
                'label' => 'Équipé d\'un compacteur ?',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PoubelleConnectee::class,
        ]);
    }
}
