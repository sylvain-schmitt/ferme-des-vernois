<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="app_cart")
     */
    public function cart(CartService $cartService): Response
    {
        return $this->render('home/cart.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    /**
     * @Route("/panier/add_product/{id}", name="app_cart_add_product", methods={"GET"})
     * @return JsonResponse
     */
    public function addProduct($id, CartService $cartService): Response
    {

        $cartService->addProduct($id);


        return $this->json([
            'code' => 200,
            'message' => 'ok',
        ], 200);
    }

    /**
     * @Route("/panier/remove_product/{id}", name="app_cart_remove_product")
     */
    public function removeProduct($id, CartService $cartService): Response
    {

        $cartService->removeProduct($id);

        return $this->redirectToRoute('app_cart');

    }

    /**
     * @Route("/panier/add_quantity/{id}", name="app_cart_add_quantity")
     */
    public function addQuantity($id, CartService $cartService): Response
    {

        $cartService->addQuantity($id);

        return $this->redirectToRoute('app_cart');

    }

    /**
     * @Route("/panier/remove_quantity/{id}", name="app_cart_remove_quantity")
     */
    public function removeQuantity($id, CartService $cartService): Response
    {

        $cartService->removeQuantity($id);

        return $this->redirectToRoute('app_cart');

    }

}
