<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private CategoryRepository $categoryRepository;

    public function __construct(
       CategoryRepository $categoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/commande", name="app_order")
     */
    public function order(): Response
    {

        return $this->render('home/order.html.twig', [
            'categories' => $this->categoryRepository->retrieveHydratedCategories()
        ]);

    }
}
