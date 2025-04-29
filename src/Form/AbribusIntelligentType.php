<?php

namespace App\Form;

use App\Entity\AbribusIntelligent;
use App\Entity\ObjetConnecte;
//use App\Entity\Zone;
//use App\Repository\ZoneRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour l'entité AbribusIntelligent.
 */
class AbribusIntelligentType extends AbstractType
{
    /**
     * Construction du formulaire.
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Ajout du champ nom
            ->add('objet', EntityType::class, [
                'class' => ObjetConnecte::class,
                'choice_label' => 'nom',
                'label' => 'Objet connecté lié',
            ])
            // Ajout du champ zone
            ->add('zone', EntityType::class, [
                'class' => Zone::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Sélectionnez une zone (optionnel)',
                'query_builder' => function (ZoneRepository $zoneRepository) {
                    return $zoneRepository->createQueryBuilder('z')
                        ->orderBy('z.nom', 'ASC');
                },
                'mapped' => false, 
            ])
            // Ajout du champ pour les Prochains passages
            ->add('prochains_passages', TextareaType::class, [
                'label' => 'Prochains passages',
                'required' => false,
                'attr' => [
                    'rows' => 4
                ]
            ])
            // Ajout du champ pour l'etat de l'ecran
            ->add('ecran_fonctionnel', CheckboxType::class, [
                'label' => 'Écran fonctionnel',
                'required' => false,
            ])
            // Ajout du champ pour les informations affichées
            ->add('informations_affichees', TextareaType::class, [
                'label' => 'Informations affichées',
                'required' => false,
                'attr' => [
                    'rows' => 4
                ]
            ])
            // Bouton de soumission du formulaire
            ->add('saveAbribus', SubmitType::class, [
                'label' => 'Ajouter l\'abribus intelligent'
            ]);
    }

    /**
     * Configuration des options du formulaire.
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AbribusIntelligent::class,
        ]);
    }
}
