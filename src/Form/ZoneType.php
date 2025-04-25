<?php

namespace App\Form;

use App\Entity\Zone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ZoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la zone',
                'attr' => ['placeholder' => 'Ex: Centre-ville, Quartier Nord...']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 4]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de zone',
                'choices' => [
                    'Quartier' => 'quartier',
                    'Rue' => 'rue',
                    'Carrefour' => 'carrefour',
                    'Parc' => 'parc',
                    'Place' => 'place',
                    'Bâtiment' => 'batiment',
                    'Autre' => 'autre'
                ]
            ])
            ->add('coordonnees', TextType::class, [
                'label' => 'Coordonnées géographiques',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: 48.8566,2.3522']
            ])
            ->add('saveZone', SubmitType::class, [
                'label' => 'Enregistrer la zone'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Zone::class,
        ]);
    }
}