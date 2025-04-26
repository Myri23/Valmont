<?php

namespace App\Form;

use App\Entity\FeuCirculation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FeuCirculationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('etatActuel', ChoiceType::class, [
                'choices' => [
                    'Vert' => 'vert',
                    'Orange' => 'orange',
                    'Rouge' => 'rouge',
                    'Clignotant' => 'clignotant',
                    'Éteint' => 'eteint',
                ],
            ])
            ->add('modeFonctionnement', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'normal',
                    'Adaptatif' => 'adaptatif',
                    'Clignotant' => 'clignotant',
                    'Éteint' => 'eteint',
                ],
            ])
            ->add('dureeCycle')
            ->add('prioriteTransportCommun')
            ->add('detectionVehicules')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FeuCirculation::class,
        ]);
    }
}
