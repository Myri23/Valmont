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

class AbribusIntelligentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('prochains_passages', TextareaType::class, [
                'label' => 'Prochains passages',
                'required' => false,
                'attr' => [
                    'rows' => 4
                ]
            ])
            ->add('ecran_fonctionnel', CheckboxType::class, [
                'label' => 'Écran fonctionnel',
                'required' => false,
            ])
            ->add('informations_affichees', TextareaType::class, [
                'label' => 'Informations affichées',
                'required' => false,
                'attr' => [
                    'rows' => 4
                ]
            ])
            ->add('saveAbribus', SubmitType::class, [
                'label' => 'Ajouter l\'abribus intelligent'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AbribusIntelligent::class,
        ]);
    }
}
