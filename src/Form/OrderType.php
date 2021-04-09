<?php

namespace App\Form;

use App\Entity\Order;
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
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom:'
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom:'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse:'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville:'
                ]
            ])
            ->add('zip', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code postal:'
                ]
            ])
            ->add('phone', PhoneNumberType::class, [
                'label' => false,
                'widget' => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
                'country_choices' => [
                    'FR',
                ],
                'preferred_country_choices' => [
                    'Fr'
                ],
                'number_options' => [
                    'attr' => [
                        'placeholder' => 'Téléphone:'
                    ]
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'mail:'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'validation_groups' => ['add_firm']
        ]);
    }
}
