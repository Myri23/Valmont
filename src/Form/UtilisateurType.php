<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('age')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                ],
                'expanded' => true,
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('typeMembre', ChoiceType::class, [
                'choices' => [
                    'Développeur' => 'dev',
                    'Testeur' => 'test',
                    'Chef de Projet' => 'chef',
                    'Directeur' => 'dir',
                ],
                'expanded' => true,
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('nom')
            ->add('prenom')
            ->add('motDePasse', PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
