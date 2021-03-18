<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/nouvelle_categorie/", name="app_admin_new_categorie")
     */
    public function newCategory(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $categoryRepository);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Categorie créer avec succès');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/new_category.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/modifier_categorie/{id}", name="app_admin_edit_category")
     */
    public function editStreamer(Category $category, Request $request, CategoryRepository $categoryRepository)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Categorie modifier avec succès');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_category.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

}
