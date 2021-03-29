<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ActualityRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FlashyNotifier $flashy;
    private ActualityRepository $actualityRepository;


    public function __construct(
        EntityManagerInterface $entityManager,
        FlashyNotifier $flashy,
        ActualityRepository $actualityRepository)
    {
        $this->entityManager = $entityManager;
        $this->flashy = $flashy;
        $this->actualityRepository = $actualityRepository;
    }

    /**
     * @Route("/admin/nouvelle_categorie/", name="app_admin_new_categorie")
     */
    public function newCategory(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->flashy->success('Catégorie créer');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/edit_category.html.twig', [
            'form' => $form->createView(),
            'actuality' => $this->actualityRepository->findBy(['active' => true])
        ]);
    }

    /**
     * @Route("/admin/modifier_categorie/{id}", name="app_admin_edit_category")
     */
    public function editCategory(Category $category, Request $request)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->flashy->info('Catégorie modifier');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_category.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'actuality' => $this->actualityRepository->findBy(['active' => true])
        ]);
    }

}
