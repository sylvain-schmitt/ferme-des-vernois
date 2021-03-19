<?php

namespace App\Controller\Admin;

use App\Entity\Unit;
use App\Form\UnitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UnitController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/nouvelle_unite/", name="app_admin_new_unit")
     */
    public function newUnit(Request $request)
    {
        $unit = new Unit();
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($unit);
            $this->entityManager->flush();
            $this->addFlash('success', 'Unité créer avec succès');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/edit_unit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/modifier_unit/{id}", name="app_admin_edit_unit")
     */
    public function editUnit(Unit $unit, Request $request)
    {
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Unité modifier avec succès');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_unit.html.twig', [
            'units' => $unit,
            'form' => $form->createView(),
        ]);
    }
}
