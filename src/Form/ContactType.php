<?php

namespace App\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Nom:'
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Prénom:'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Adresse:'
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
                        'placeholder'=> 'Téléphone:'
                    ]
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder'=> 'E-Mail:'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['rows' => '8',
                            'placeholder'=> 'Message:'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['add_firm']
        ]);
    }
}
