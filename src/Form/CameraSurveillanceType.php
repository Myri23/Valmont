<?php

namespace App\Form;

use App\Entity\CameraSurveillance;
use App\Entity\ObjetConnecte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CameraSurveillanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet', EntityType::class, [
                'class' => ObjetConnecte::class,
                'choice_label' => 'nom',
                'label' => 'Objet connecté lié',
            ])
            ->add('resolution', TextType::class, [
                'required' => false,
            ])
            ->add('detection_mouvement', CheckboxType::class, [
                'required' => false,
                'label' => 'Détection de mouvement ?',
            ])
            ->add('vision_nocturne', CheckboxType::class, [
                'required' => false,
                'label' => 'Vision nocturne ?',
            ])
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
            ->add('saveCamera', SubmitType::class, [
                'label' => 'Ajouter la caméra',
]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CameraSurveillance::class,
        ]);
    }
}
