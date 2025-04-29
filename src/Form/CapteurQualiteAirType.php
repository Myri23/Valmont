<?php

namespace App\Form;

// Importation des types de formulaire nécessaires
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\CapteurQualiteAir;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Formulaire pour la gestion des capteurs de qualité de l'air
 * Cette classe permet de créer et modifier les paramètres des capteurs
 * mesurant la qualité de l'air environnant
 */
class CapteurQualiteAirType extends AbstractType
{
    /**
     * Construction du formulaire avec tous les champs nécessaires
     * @param FormBuilderInterface $builder Constructeur de formulaire
     * @param array $options Options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Association avec un objet connecté
            ->add('objet')
            
            // Niveau de particules fines PM2.5 (diamètre < 2.5 µm)
            ->add('niveauPm25')
            
            // Niveau de particules fines PM10 (diamètre < 10 µm)
            ->add('niveauPm10')
            
            // Niveau de dioxyde de carbone (CO2)
            ->add('niveauCo2')
            
            // Niveau d'ozone (O3)
            ->add('niveauO3')
            
            // Indice global de la qualité de l'air
            ->add('qualiteGlobale')
            
            // Date et heure de la dernière mesure effectuée
            ->add('derniereMesure', DateTimeType::class, [
                'widget' => 'single_text', // Affichage en un seul champ HTML5
            ])
        ;
    }

    /**
     * Configure les options par défaut du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CapteurQualiteAir::class, // Liaison avec l'entité CapteurQualiteAir
        ]);
    }
}
