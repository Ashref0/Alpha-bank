<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\CarteBancaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\DateType;

// Ensure other necessary use statements are present as well

class CarteBancaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Num_carte', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^(4|5)\\d{15}$/',
                        'message' => 'Le numéro de carte de crédit doit commencer par 4 ou 5 et contenir uniquement des chiffres, des espaces ou des tirets.',
                    ]),   
                ],
            ])
            ->add('Date_Exp', DateType::class, [
                'widget' => 'choice',
                'format' => 'dd MM yyyy',
                'years' => range(2024, 2034), // Adjusted range
                'label' => 'Date d\'expiration',
                // Include other options as needed...
            ])
            ->add('cvv', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'exactMessage' => 'Le CVV doit être exactement de {{ limit }} caractères.',
                    ]),
                   
                ],
                'label' => 'CVV',
            ])
            ->add('compte');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CarteBancaire::class,
        ]);
    }
}
