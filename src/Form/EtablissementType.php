<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Compte;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class EtablissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (isset($data['num_contact'])) {
                // Convert num_contact from string to integer
                $data['num_contact'] = (int) $data['num_contact'];
                $event->setData($data);
            }
        });

        $builder
            ->add('Nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le nom doit contenir uniquement des lettres et des espaces.',
                    ]),
                ],
            ])
            ->add('adresse', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'adresse ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'L\'adresse doit contenir au moins 5 caractères.',
                    ]),
                ],
            ])
            ->add('num_contact', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le numero du contact ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^216\d{8}$/',
                        'message' => 'Le numéro de contact doit commencer par 216 et contenir 11 chiffres au total.',
                    ]),
                ],
            ])
            // Add the EntityType field for choosing the Compte
            ->add('compte', EntityType::class, [
                'class' => Compte::class,
                'choice_label' => 'num_compte', // Change this to the property you want to display
                'label' => 'Compte',
                // Additional options as needed
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablissement::class,
        ]);
    }
}
