<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderId', HiddenType::class)
            ->add('last_name', TextType::class, [
                'label' => 'Nom de Famille'
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('address', EmailType::class, [
                'label' => 'Adresse mail'
            ])
            ->add('product', EntityType::class, [
                'expanded' => true,
                'required' => false,
                'class' => Product::class,
                'multiple' => true,
                'query_builder' => function (ProductRepository $productRepository){
                    return $productRepository->createQueryBuilder('product')
                        ->where('product.active = true')
                    ;
                },
                'choice_label' => function (Product $product) {
                    return $product->getTitle();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
