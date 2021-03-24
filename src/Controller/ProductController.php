<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/commande", name="app_pre_order")
     */
    public function order(): Response
    {

        return $this->render('home/pre_order.html.twig', [
            'products' => $this->productRepository->findBy(['active' => true])
        ]);

    }
}
