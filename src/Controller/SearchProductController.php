<?php

namespace App\Controller;

use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;



class SearchProductController extends AbstractController
{

    private ProductRepository $productRepository;
    private FlashyNotifier $flashy;

    public function __construct(
        ProductRepository $productRepository,
        FlashyNotifier $flashy
    )
    {
        $this->productRepository = $productRepository;
        $this->flashy = $flashy;
    }

    /**
     *@Route("/search", name="app_search")
     */
    public function search(Request $request): Response
    {
        $products = null;
        $form = $this->createForm(SearchProductType::class);
        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $products = $this->productRepository->search(
                $search->get('words')->getData(),
                $search->get('category')->getData()
            );
            if(!$products){
                $this->flashy->info('Aucuns produit trouver !');
            }
        }

        return $this->render('home/search.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }
}