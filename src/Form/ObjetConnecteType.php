<?php

namespace App\Form;

use App\Entity\ObjetConnecte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjetConnecteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idUnique', TextType::class)
            ->add('nom', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('type', TextType::class)
            ->add('marque', TextType::class, [
                'required' => false,
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                    'Connecté' => 'Connecté',
                    'Déconnecté' => 'Déconnecté',
                    'Maintenance' => 'Maintenance',
                ]
            ])
            ->add('localisation', TextType::class, [
                'required' => false,
            ])
            ->add('derniereInteraction', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('connectivite', TextType::class, [
                'required' => false,
            ])
            ->add('batteriePct', IntegerType::class, [
                'required' => false,
                'label' => 'Batterie (%)',
            ])
            ->add('actif', CheckboxType::class, [
                'required' => false,
                'label' => 'Actif ?',
            ])
             ->add('saveObjet', SubmitType::class, [
            'label' => 'Ajouter l\'objet connecté',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ObjetConnecte::class,
        ]);
    }
}
