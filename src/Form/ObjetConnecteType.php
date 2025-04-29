<?php
namespace App\Form;

// Importation des classes nécessaires
use App\Entity\ObjetConnecte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de gestion des objets connectés
 * Classe de base pour tous les objets IoT du système
 */
class ObjetConnecteType extends AbstractType
{
    /**
     * Construction du formulaire avec les champs communs à tous les objets connectés
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Type d'objet connecté (caché)
            ->add('type', HiddenType::class)
            
            // Nom de l'objet connecté
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control']
            ])
            // Marque de l'objet
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr' => ['class' => 'form-control']
            ])
            // État de fonctionnement de l'objet
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => [
                    'Fonctionnel' => 'Fonctionnel',
                    'En maintenance' => 'En maintenance',
                    'Hors service' => 'Hors service'
                ],
                'attr' => ['class' => 'form-select']
            ])
            // Localisation physique de l'objet
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'attr' => ['class' => 'form-control']
            ])
            // Niveau de batterie en pourcentage
            ->add('batteriePct', NumberType::class, [
                'label' => 'Niveau de batterie (%)',
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                    'class' => 'form-control'
                ]
            ])
            // État d'activation de l'objet
            ->add('actif', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
        ;
    }

    /**
     * Configuration des options par défaut du formulaire
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjetConnecte::class, // Liaison avec l'entité ObjetConnecte
        ]);
    }
}
