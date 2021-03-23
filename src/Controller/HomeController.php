<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Repository\ActualityRepository;
use App\Repository\OrderRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
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
    private FlashyNotifier $flashy;
    private ActualityRepository $actualityRepository;

    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository,
        FlashyNotifier $flashy,
        ActualityRepository $actualityRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
        $this->flashy = $flashy;
        $this->actualityRepository = $actualityRepository;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [
            'actuality' => $this->actualityRepository->findBy(['active' => true])
        ]);

    }

    /**
     * @Route("/produit", name="app_product")
     */
    public function product(Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $this->productRepository->findBy(['active' => true], ['createdAt' => 'desc']);

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
    public function categoryShow(Category $category): Response
    {
        $products = $this->productRepository->findByCategory($category);
        return $this->render('home/category_show.html.twig', compact('products', 'category'));
    }

    /**
     * @Route("/commande", name="app_order")
     */
    public function order(Request $request, MailerInterface $mailer): Response
    {

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Todo : Crée le PDF avec toute les infos récupérer pour les produits
            // Todo : Set le PDF en BDD
            $email = (new TemplatedEmail())
                ->from('fermedesvernois@test.com')
                ->to($order->getAddress())
                ->subject('contact depuis la ferme des vernois')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'id' => $order->getId(),
                    'address' => $order->getAddress(),
                    'nom' => $order->getFirstName(),
                    'prenom' => $order->getLastName(),
                    'phone' => $order->getPhone(),
                    'sujet' => ('nouvelle commande'),
                ])
            ;

            $mailer->send($email);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            $this->flashy->success('Votre mail à bien été envoyer !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/order.html.twig', [
            'products' => $this->productRepository->findBy(['active' => true]),
            'form' => $form->createView()
        ]);
    }

}
