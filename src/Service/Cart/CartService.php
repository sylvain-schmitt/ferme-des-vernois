<?php


namespace App\Service\Cart;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService

{
    private SessionInterface $session;
    private ProductRepository $productRepository;

    public function __construct(
        SessionInterface $session,
        ProductRepository $productRepository
    )
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function getFullCart(): array
    {
        // On appel le panier (si il est remplis) sinon afficher un tableau vide
        $panier = $this->session->get('panier', []);

        $panier_data = [];

        foreach ($panier as $id => $quantity) {
            $panier_data[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panier_data;
    }

    public function getQuantity(): array
    {
        // On appel le panier (si il est remplis) sinon afficher un tableau vide
        $panier = $this->session->get('panier', []);

        $panier_data = [];

        foreach ($panier as $id => $quantity) {
            $panier_data[] = [
                'quantity' => $quantity
            ];
        }
        return $panier_data;
    }

    public function addProduct(int $id)
    {

        // On créer le panier
        $panier = $this->session->get('panier', []);

        // On regarde si un produit est déjà dans le panier, si il est dans le panier alors quantité +1 sinon quantité = 1
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // On envoie les données à la session
        $this->session->set('panier', $panier);

    }

    public function removeProduct(int $id)
    {

        // On créer le panier
        $panier = $this->session->get('panier', []);

        // On regarde si un produit est déjà dans le panier, si il est dans le panier alors quantité -1 sinon on supprime le produit du panier
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On envoie les données à la session
        $this->session->set('panier', $panier);

    }

    public function addQuantity(int $id)
    {

        // On créer le panier
        $panier = $this->session->get('panier', []);

        // On regarde si un produit est déjà dans le panier, si il est dans le panier alors quantité +1
        if (!empty($panier[$id])) {
            $panier[$id]++;
        }

        // On envoie les données à la session
        $this->session->set('panier', $panier);

    }

    public function removeQuantity(int $id)
    {

        // On créer le panier
        $panier = $this->session->get('panier', []);

        // On regarde si un produit est déjà dans le panier, si il est dans le panier alors quantité -1 sinon quantité = 1

        if ($panier[$id] < 2) {
            unset($panier[$id]);
        } elseif (!empty($panier[$id])) {
            $panier[$id]--;
        }

        // On envoie les données à la session
        $this->session->set('panier', $panier);

    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

}