<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom du Produit'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix TTC'
            ])
            ->add('pound', TextType::class, [
                'label' => 'Poids'
            ])
            ->add('category')
            ->add('tva')
            ->add('units')
            ->add('quantity', TextType::class, [
                'label' => 'Quantité'
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Activer ou Désactiver le Produit',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ]
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
