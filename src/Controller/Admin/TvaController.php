<?php

namespace App\Controller\Admin;

use App\Entity\Tva;
use App\Form\TvaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TvaController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/modifier_tva/{id}", name="app_admin_edit_tva")
     */
    public function editStreamer(Tva $tva, Request $request)
    {
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'TVA modifier avec succès');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_tva.html.twig', [
            'tvas' => $tva,
            'form' => $form->createView(),
        ]);
    }
}
