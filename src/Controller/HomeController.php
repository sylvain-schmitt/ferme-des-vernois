<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProductRepository $productRepository): Response
    {
       $products = $productRepository->findBy(['active' => '1'], ['createdAt'=>'desc'],3 );
        return $this->render('home/index.html.twig', compact('products'));
    }

    /**
     * @Route("/produit", name="app_product")
     */
    public function product(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('home/product.html.twig', compact('products'));
    }

    /**
     * @Route("/produit/{slug}", name="app_product_show")
     */
    public function productShow(Product $product): Response
    {
        return $this->render('home/product_show.html.twig', compact('product'));
    }

    /**
     * @Route("/categorie/{slug}", name="app_category")
     */
    public function categoryShow(ProductRepository $productRepository): Response
    {
        $products= $productRepository->findBy(['categoryId'],[]);
        return $this->render('home/category_show.html.twig', compact('products'));
    }

}
