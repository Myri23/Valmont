<?php

namespace App\Form;

// Importation des classes nécessaires
use App\Entity\PoubelleConnectee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Formulaire de gestion des poubelles connectées
 * Cette classe permet de configurer les paramètres des poubelles intelligentes
 */
class PoubelleConnecteeType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Niveau de remplissage actuel de la poubelle
            ->add('niveauRemplissage')
            // Capacité totale de la poubelle
            ->add('capaciteTotale')
            // Type de déchets acceptés par la poubelle
            ->add('typeDechets', ChoiceType::class, [
                'choices' => [
                    'Ordures' => 'ordures',
                    'Recyclable' => 'recyclable',
                    'Verre' => 'verre',
                    'Compost' => 'compost',
                    'Mixte' => 'mixte',
                ],
            ])
            // Date et heure de la dernière collecte
            ->add('derniereCollecte', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            // Présence d'un système de compactage
            ->add('compacteur')
        ;
    }

    /**
     * Configure les options par défaut du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PoubelleConnectee::class, // Liaison avec l'entité PoubelleConnectee
        ]);
    }
}
