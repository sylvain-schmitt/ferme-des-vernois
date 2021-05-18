<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', FileType::class,[
                'label' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '10485k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png'
                            ],
                        ])
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png',
                    'class' => 'form-control'
                ],
            ])
            ->add('alt', TextType::class,[
                'label' => 'Déscription de l\'image',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
