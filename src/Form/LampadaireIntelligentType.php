<?php

namespace App\Form;

use App\Entity\LampadaireIntelligent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LampadaireIntelligentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('intensiteLumineuse')
            ->add('modeEclairage', ChoiceType::class, [
                'choices' => [
                    'Fixe' => 'fixe',
                    'Adaptatif' => 'adaptatif',
                    'Économie' => 'economie',
                    'Éteint' => 'eteint',
                ],
            ])
            ->add('capteurMouvement')
            ->add('capteurLuminosite')
            ->add('heuresFonctionnement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LampadaireIntelligent::class,
        ]);
    }
}
