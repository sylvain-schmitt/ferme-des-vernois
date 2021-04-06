<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ActualityRepository;
use App\Repository\CategoryRepository;
use App\Repository\GalleryRepository;
use App\Repository\OrderRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;
    private FlashyNotifier $flashy;
    private ActualityRepository $actualityRepository;
    private GalleryRepository $galleryRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository,
        FlashyNotifier $flashy,
        ActualityRepository $actualityRepository,
        GalleryRepository $galleryRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
        $this->flashy = $flashy;
        $this->actualityRepository = $actualityRepository;
        $this->galleryRepository = $galleryRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [
            'actuality' => $this->actualityRepository->findBy(['active' => true]),
            'categories' => $this->categoryRepository->findBy([], ['id' => 'DESC'])
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
     * @Route("/contact", name="app_contact")
     */
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/galerie", name="app_gallery")
     */
    public function gallery(): Response
    {
        return $this->render('home/gallery.html.twig', [
            'gallerys' => $this->galleryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ferme-des-vernois", name="app_about")
     */
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

}
