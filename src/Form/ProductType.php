<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom du Produit'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Déscription'
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix TTC'
            ])
            ->add('pound', TextType::class, [
                'label' => 'Poids (xx.xx)',
                'attr' => [
                    'pattern' => '[0-9]{1,5}[.]{0,1}[0-9]{0,2}'
                ]
            ])
            ->add('category')
            ->add('tva')
            ->add('units')
            ->add('quantity', TextType::class, [
                'label' => 'Quantité'
            ])

            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG ou PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_uri' => false,
                'image_uri' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
