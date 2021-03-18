<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\TvaRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, TvaRepository $tvaRepository, UnitRepository $unitRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'tvas' => $tvaRepository->findAll(),
            'units' => $unitRepository->findAll(),
        ]);
    }



}
