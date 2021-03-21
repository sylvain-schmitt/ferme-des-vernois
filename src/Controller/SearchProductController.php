<?php

namespace App\Controller;

use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class SearchProductController extends AbstractController
{
    /**
     *@Route("/search", name="app_search")
     */
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $products = null;
        $form = $this->createForm(SearchProductType::class);
        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $products = $productRepository->search(
                $search->get('words')->getData(),
            );
            if (!$products) {
                throw new NotFoundHttpException('Aucuns produits trouvée');
            }
        }


        return $this->render('home/search.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }
}