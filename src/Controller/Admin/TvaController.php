<?php

namespace App\Controller\Admin;

use App\Entity\Tva;
use App\Form\TvaType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TvaController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FlashyNotifier $flashy;


    public function __construct(EntityManagerInterface $entityManager, FlashyNotifier $flashy)
    {
        $this->entityManager = $entityManager;
        $this->flashy = $flashy;
    }

    /**
     * @Route("/admin/nouvelle_tva/", name="app_admin_new_tva")
     */
    public function newTva(Request $request)
    {
        $tva = new Tva();
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($tva);
            $this->entityManager->flush();
            $this->flashy->success('Tva créer');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/edit_tva.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/modifier_tva/{id}", name="app_admin_edit_tva")
     */
    public function editTva(Tva $tva, Request $request)
    {
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->flashy->info('Tva modifier');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_tva.html.twig', [
            'tva' => $tva,
            'form' => $form->createView(),
        ]);
    }
}
