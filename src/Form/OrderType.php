<?php

namespace App\Form;

use App\Entity\Order;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name', TextType::class, [
                'label' => 'Nom de Famille',
                'attr' => [
                    'value' => 'Nom de test'
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'value' => 'Prénom de test'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'value' => 'Adresse Postal de test'
                ]
            ])
            ->add('city',TextType::class, [
                'label' => 'ville',
                'attr' => [
                    'value' => 'Vesoul'
                ]
            ])
            ->add('zip',TextType::class, [
                'label' => 'code postal',
                'attr' => [
                    'value' => '70000'
                ]
            ])
            ->add('phone', PhoneNumberType::class, [
                'label' => 'Numéro de téléphone',
                'widget' => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
                'country_choices' => [
                    'FR',
                ],
                'preferred_country_choices' => [
                    'Fr'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail',
                'attr' => [
                    'value' => 'mail@test.com'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'validation_groups' => ['add_firm']
        ]);
    }
}
