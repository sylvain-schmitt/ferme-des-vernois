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
    /**
     *@Route("/search", name="app_search")
     */
    public function search(Request $request, ProductRepository $productRepository, FlashyNotifier $flashy): Response
    {
        $products = null;
        $form = $this->createForm(SearchProductType::class);
        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $products = $productRepository->search(
                $search->get('words')->getData(),
                $search->get('category')->getData()
            );
            if(!$products){
                $flashy->info('Aucuns produit trouver !');
            }
        }

        return $this->render('home/search.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }
}