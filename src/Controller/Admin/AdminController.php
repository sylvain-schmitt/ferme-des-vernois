<?php

namespace App\Controller\Admin;

use App\Entity\Actuality;
use App\Form\ActualityType;
use App\Repository\ActualityRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private UnitRepository $unitRepository;
    private ActualityRepository $actualityRepository;
    private EntityManagerInterface $entityManager;
    private FlashyNotifier $flashy;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        UnitRepository $unitRepository,
        EntityManagerInterface $entityManager,
        FlashyNotifier $flashy,
        ActualityRepository $actualityRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->unitRepository = $unitRepository;
        $this->entityManager = $entityManager;
        $this->flashy = $flashy;
        $this->actualityRepository = $actualityRepository;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'products' => $this->productRepository->findBy([], ['createdAt' => 'DESC']),
            'categories' => $this->categoryRepository->findAll(),
            'units' => $this->unitRepository->findAll(),
            'actuality' => $this->actualityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/actualite/{id}", name="app_admin_edit_actu")
     */
    public function actualtiEdit(Request $request, Actuality $actuality): Response
    {

        $form = $this->createForm(ActualityType::class, $actuality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->flashy->info('Annonce modifier');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/actuality.html.twig', [
            'actuality' => $actuality,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/actualite/activer/{id}", name="activer_actuality")
     */
    public function activerActuality(Actuality $actuality)
    {
        $actuality->setActive(($actuality->getActive()) ? false : true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($actuality);
        $em->flush();

        return new Response("true");
    }

}
