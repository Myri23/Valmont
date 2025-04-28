<?php
namespace App\Form;

use App\Entity\ObjetConnecte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjetConnecteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('type', HiddenType::class, [
                'data' => 'Poubelle',
            ])
            
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr' => ['class' => 'form-control']
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => [
                    'Fonctionnel' => 'Fonctionnel',
                    'En maintenance' => 'En maintenance',
                    'Hors service' => 'Hors service'
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'attr' => ['class' => 'form-control']
            ])
            ->add('batteriePct', NumberType::class, [
                'label' => 'Niveau de batterie (%)',
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                    'class' => 'form-control'
                ]
            ])
            ->add('actif', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
        ;

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjetConnecte::class,
        ]);
    }
}
