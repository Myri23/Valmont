<?php

namespace App\Form;

use App\Entity\ParkingIntelligent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParkingIntelligentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('places_totales', IntegerType::class, [
                'label' => 'Nombre total de places',
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('places_disponibles', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('localisation_precise', TextType::class, [
                'label' => 'Localisation précise'
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParkingIntelligent::class,
        ]);
    }
}
