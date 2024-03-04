<?php

namespace App\Form;

use App\Entity\Remboursement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RemboursementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Montant', null, [
        'label' => 'Montant',
        'constraints' => [
            new NotBlank(['message' => 'Please enter a value.'])]
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remboursement::class,
        ]);
    }
}
