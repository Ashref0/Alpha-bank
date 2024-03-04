<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Etablissement; // Import the Etablissement class


class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num_compte', TextType::class, [
                'disabled' => true,
                'attr' => ['readonly' => true]
            ])
            ->add('Type_compte', ChoiceType::class, [
                'choices' => [
                    'Epargne' => 'Epargne',
                    'Courant' => 'Courant',
                ],
                'label' => 'Type de compte',
                // Add any other options you might need
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}