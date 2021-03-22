<?php

namespace App\Controller\Admin;

use App\Entity\Unit;
use App\Form\UnitType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UnitController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FlashyNotifier $flashy;

    public function __construct(EntityManagerInterface $entityManager, FlashyNotifier $flashy)
    {
        $this->entityManager = $entityManager;
        $this->flashy = $flashy;
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
            $this->flashy->success('Unité créer');
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
            $this->flashy->info('Unité modifier');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_unit.html.twig', [
            'units' => $unit,
            'form' => $form->createView(),
        ]);
    }
}
