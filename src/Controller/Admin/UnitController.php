<?php

namespace App\Controller\Admin;

use App\Entity\Unit;
use App\Form\UnitType;
use App\Repository\ActualityRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnitController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FlashyNotifier $flashy;
    private ActualityRepository $actualityRepository;
    private UnitRepository $unitRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashyNotifier $flashy,
        ActualityRepository $actualityRepository,
        UnitRepository $unitRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->flashy = $flashy;
        $this->actualityRepository = $actualityRepository;
        $this->unitRepository = $unitRepository;
    }

    /**
     * @Route("/admin/unitee", name="app_admin_all_units")
     */
    public function allUnits(): Response
    {
        return $this->render('admin/all_unit.html.twig', [
            'units' => $this->unitRepository->findAll()
        ]);
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
            'actuality' => $this->actualityRepository->findBy(['active' => true])
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
            'actuality' => $this->actualityRepository->findBy(['active' => true])
        ]);
    }
}
