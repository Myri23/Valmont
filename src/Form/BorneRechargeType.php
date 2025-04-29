<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// Importation des classes nécessaires
use App\Entity\BorneRecharge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la gestion des bornes de recharge
 * Cette classe gère la création et la modification des bornes de recharge
 */
class BorneRechargeType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Association avec un objet connecté
            ->add('objet')
            // Puissance maximale de la borne en kW
            ->add('puissanceMax')
            // Type de connecteur disponible sur la borne
            ->add('typeConnecteur')
            // Nombre de points de charge disponibles
            ->add('nombrePointsCharge')
            // Statut d'occupation de la borne
            ->add('statusOccupation', ChoiceType::class, [
                'choices' => [
                    'Libre' => 'libre',
                    'Occupé' => 'occupé',
                    'Hors service' => 'hors service',
                ],
            ])
            // Prix du kilowattheure
            ->add('prixKwh')
            // Temps moyen de charge en minutes
            ->add('tempsChargeMoyen')
        ;
    }

    /**
     * Configuration des options du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BorneRecharge::class, // Liaison avec l'entité BorneRecharge
        ]);
    }
}
