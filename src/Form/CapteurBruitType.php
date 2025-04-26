<?php

namespace App\Form;

use App\Entity\CapteurBruit;
use App\Entity\ObjetConnecte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CapteurBruitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet', EntityType::class, [
                'class' => ObjetConnecte::class,
                'choice_label' => 'nom',
                'label' => 'Objet connecté lié',
            ])
            ->add('niveau_decibel', NumberType::class, [
                'label' => 'Niveau de décibel',
                'required' => false,
                'attr' => [
                    'step' => '0.1'
                ]
            ])
            ->add('seuil_alerte', NumberType::class, [
                'label' => 'Seuil d\'alerte (dB)',
                'required' => false,
                'attr' => [
                    'step' => '0.1'
                ]
            ])
            ->add('derniere_alerte', DateTimeType::class, [
                'label' => 'Dernière alerte',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('saveCapteurBruit', SubmitType::class, [
                'label' => 'Ajouter le capteur de bruit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CapteurBruit::class,
        ]);
    }
}
