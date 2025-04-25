<?php

namespace App\Form;

use App\Entity\ParkingIntelligent;
use App\Entity\ObjetConnecte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('objet', EntityType::class, [
                'class' => ObjetConnecte::class,
                'choice_label' => 'nom',
                'label' => 'Objet connecté lié',
            ])
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
            ])
            ->add('saveParking', SubmitType::class, [
                'label' => 'Ajouter le parking'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParkingIntelligent::class,
        ]);
    }
}