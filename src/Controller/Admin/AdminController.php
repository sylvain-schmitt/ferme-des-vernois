<?php

namespace App\Controller\Admin;

use App\Repository\ActualityRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private ActualityRepository $actualityRepository;
    private EntityManagerInterface $entityManager;
    private FlashyNotifier $flashy;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashyNotifier $flashy,
        ActualityRepository $actualityRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->flashy = $flashy;
        $this->actualityRepository = $actualityRepository;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'actuality' => $this->actualityRepository->findAll(),
        ]);
    }

}
