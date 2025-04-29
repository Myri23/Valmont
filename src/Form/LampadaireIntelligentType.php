<?php

namespace App\Form;

use App\Entity\LampadaireIntelligent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LampadaireIntelligentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heureAllumage', TimeType::class, [
                'label' => 'Heure d\'allumage',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('dureeAllumage', IntegerType::class, [
                'label' => 'Durée d\'allumage (minutes)',
                'attr' => [
                    'min' => 0,
                    'max' => 1440 // 24 heures en minutes
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LampadaireIntelligent::class,
        ]);
    }
}
