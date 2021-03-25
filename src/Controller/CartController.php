<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="app_cart")
     */
    public function cart(
        CartService $cartService,
        Request $request,
        EntityManagerInterface $entityManager,
        FlashyNotifier $flashy,
        SessionInterface $session,
        ProductRepository $productRepository
    ): Response
    {

        $order_id = $this->generate();
        $product = $cartService->getFullCart();

        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($product as $value){
                $order = (new Order())
                    ->setFirstName($form->get('first_name')->getData())
                    ->setLastName($form->get('last_name')->getData())
                    ->setPhone($form->get('phone')->getData())
                    ->setAddress($form->get('address')->getData())
                    ->setEmail($form->get('email')->getData())
                    ->setOrderId($order_id)
                    ->setProduct($value['product'])
                    ->setQuantity($value['quantity']);
                $entityManager->persist($order);

                $products = $productRepository->findOneBy(["id" => $value['product'] ]);
                $quantity = $products->getQuantity() - $value['quantity'];
                $products->setQuantity($quantity);
                $entityManager->flush();

            }

            $session->remove('panier');
            $flashy->success('Votre commande à bien été prise en compte');
            return $this->redirectToRoute('app_home');
        }


        return $this->render('home/cart.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'form' => $form->createView(),
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

    private function generate(int $length= 12): string
    {
        return substr(
            bin2hex(random_bytes((int) ceil($length / 2))),
            0, $length
        );
    }

}
