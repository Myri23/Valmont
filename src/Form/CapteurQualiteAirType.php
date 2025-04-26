<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\CapteurQualiteAir;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CapteurQualiteAirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('niveauPm25')
            ->add('niveauPm10')
            ->add('niveauCo2')
            ->add('niveauO3')
            ->add('qualiteGlobale')
            ->add('derniereMesure', DateTimeType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CapteurQualiteAir::class,
        ]);
    }
}
