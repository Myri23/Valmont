<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use App\Entity\BorneRecharge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorneRechargeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('puissanceMax')
            ->add('typeConnecteur')
            ->add('nombrePointsCharge')
            ->add('statusOccupation', ChoiceType::class, [
                'choices' => [
                    'Libre' => 'libre',
                    'Occupé' => 'occupé',
                    'Hors service' => 'hors service',
                ],
            ])
            ->add('prixKwh')
            ->add('tempsChargeMoyen')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BorneRecharge::class,
        ]);
    }
}
