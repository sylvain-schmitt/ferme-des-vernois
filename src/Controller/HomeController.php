<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Component\Mime\Address;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Mailer\MailerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;

    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;

    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $products = $this->productRepository->findBy(['active' => '1'], ['createdAt' => 'desc'], 3);
        return $this->render('home/index.html.twig', compact('products'));
    }

    /**
     * @Route("/produit", name="app_product")
     */
    public function product(Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $this->productRepository->findBy([], ['createdAt' => 'desc']);

        $products = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
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
    public function categoryShow(): Response
    {
        $products = $this->productRepository->findBy(['categoryId'], []);
        return $this->render('home/category_show.html.twig', compact('products'));
    }

    /**
     * @Route("/commande", name="app_order")
     */
    public function order(Request $request, MailerServiceInterface $mailer, OrderRepository $orderRepository): Response
    {

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Todo : Crée le PDF avec toute les infos récupérer pour les produits
            // Todo : Set le PDF en BDD

            $toAdresses = [new Address($order->getAddress()), new Address('mailcentre@mail.com')];
            $mailer->send('repare_online_garage@test.fr', $toAdresses, 'Contact depuis le site Repare Online Garage',
                'emails/contact.mjml.twig',
                'emails/contact.txt.twig', [
                    'order_id' => $order->getOrderId(),
                    'nom' => $order->getFirstName(),
                    'prenom' => $order->getLastName(),
                    'phone' => $order->getPhone(),
                    'address' => $order->getAddress(),
                    // Todo : 'pdf' => $order->getPdf(),
                    // Todo : Ajouter le PDF pour l'envoyer par mail
                ]);

            $this->entityManager->persist($order);
            $this->entityManager->flush();
            $this->addFlash('message', 'Votre Mail à bien été envoyer');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/order.html.twig', [
            'products' => $this->productRepository->findBy(['active' => '1']),
            'form' => $form->createView()
        ]);
    }

}
