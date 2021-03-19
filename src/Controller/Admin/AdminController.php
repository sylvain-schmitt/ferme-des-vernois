<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\TvaRepository;
use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private TvaRepository $tvaRepository;
    private UnitRepository $unitRepository;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        TvaRepository $tvaRepository,
        UnitRepository $unitRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tvaRepository = $tvaRepository;
        $this->unitRepository = $unitRepository;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'products' => $this->productRepository->findBy([], ['createdAt' => 'DESC']),
            'categories' => $this->categoryRepository->findAll(),
            'tvas' => $this->tvaRepository->findAll(),
            'units' => $this->unitRepository->findAll(),
        ]);
    }

}
