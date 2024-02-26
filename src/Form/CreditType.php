<?php

namespace App\Form;

use App\Entity\Credit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Type', ChoiceType::class, [
                'choices' => [
                    'Personal' => 'Personal',
                    'Home' => 'Home',
                    'Business' => 'Business',
                    'Car' => 'Car',
                ],
                'label' => 'Type de Credit',
            ])
            ->add('MontantEmprunte', NumberType::class, [
                'label' => 'Montant d Emprunte',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a value.']),
                    new Range([
                        'min' => 1000,
                        'max' => 500000,
                        'minMessage' => "The minimum value for MontantEmprunte is 1000 dt.",
                        'maxMessage' => "The maximum value for MontantEmprunte is 500 000 dt.",
                    ]),
                ],
            ])
            ->add('NbMois', null, [
                'label' => 'Nombre de Mois',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a value.'])]
            ])
            ->add('DateEmission', null, [
                'label' => ' Date d Emission',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a value.'])]
            ])
            ->add('Description', null, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a value.'])]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image CIN',
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Credit::class,
        ]);
    }
}
