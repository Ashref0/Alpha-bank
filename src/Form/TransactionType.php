<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_destinataire')
            ->add('prenom_destinataire')
            ->add('iban_destinataire')
            ->add('montant')
            ->add('iban')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nom', // Property of Client entity to display in the select list
                'placeholder' => 'Choose a client', // Optional placeholder
            ])
            ->add('client2', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nom', // Property of Client entity to display in the select list
                'placeholder' => 'Choose a client', // Optional placeholder
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
