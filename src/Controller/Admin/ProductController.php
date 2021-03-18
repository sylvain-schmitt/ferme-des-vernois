<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/produit/{id}", name="app_admin_show_product")
     */
    public function showProduct(Product $product): Response
    {
        return $this->render('admin/show_product.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/admin/nouveau_produit/", name="app_admin_new_product")
     */
    public function newProduct(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $productRepository);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $this->addFlash('success', 'Produit créer avec succès');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/new_product.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/modifier_profuit/{id}", name="app_admin_edit_product")
     */
    public function editProduct(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Produit modifier avec succès');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_product.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/supprimer_produit/{id<[0-9]+>}", name="app_admin_delete_product", methods={"DELETE"})
     */
    public function deleteProduct(Request $request, Product $product)
    {
        if ($this->isCsrfTokenValid('product_deletion_' . $product->getId(), $request->request->get('csrf_token'))) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();

            $this->addFlash('info', 'Rendez vous supprimer avec succès !');
        }
        return $this->redirectToRoute('app_admin');
    }

    /**
     * @Route("/admin/add_quantity/{id<[0-9]+>}", name="app_quantity_add", methods={"GET"})
     */
    public function addQuantity(Product $product): Response
    {
        $count = $product->getQuantity();
        $count = $count + 1;
        $product->setQuantity($count);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_admin');
    }

    /**
     * @Route("/admin/delete_quantity/{id<[0-9]+>}", name="app_quantity_delete", methods={"GET"})
     */
    public function deleteQuantity(Product $product): Response
    {
        $count = $product->getQuantity();
        if ($count >= 1) {
            $count = $count - 1;
            $product->setQuantity($count);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }

    /**
     * @Route("/admin/product/activer/{id}", name="activer")
     */
    public function activer(Product $product)
    {
        $product->setActive(($product->getActive()) ? false : true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response("true");
    }

}
