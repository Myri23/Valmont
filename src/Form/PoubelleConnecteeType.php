<?php

namespace App\Form;

use App\Entity\PoubelleConnectee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoubelleConnecteeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('niveauRemplissage')
            ->add('capaciteTotale')
            ->add('typeDechets', ChoiceType::class, [
                'choices' => [
                    'Ordures' => 'ordures',
                    'Recyclable' => 'recyclable',
                    'Verre' => 'verre',
                    'Compost' => 'compost',
                    'Mixte' => 'mixte',
                ],
            ])
            ->add('derniereCollecte', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('compacteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PoubelleConnectee::class,
        ]);
    }
}
