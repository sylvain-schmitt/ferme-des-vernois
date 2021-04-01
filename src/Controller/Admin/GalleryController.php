<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private FlashyNotifier $flashy;
    private GalleryRepository $galleryRepository;


    public function __construct(
        EntityManagerInterface $entityManager,
        FlashyNotifier $flashy,
        GalleryRepository $galleryRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->flashy = $flashy;
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @Route("/admin/gallery", name="app_admin_gallery")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(GalleryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images transmises
            $images = $form->get('title')->getData();
            $alt = $form->get('alt')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new Gallery();
                $img->setTitle($fichier);
                $img->setAlt($alt);
                $this->entityManager->persist($img);
                $this->entityManager->flush();
            }


            $this->flashy->success('Image ajouter avec success');
            return $this->redirectToRoute('app_admin_gallery');
        }
        return $this->render('admin/gallery.html.twig',[
            'form' => $form->createView(),
            'images' => $this->galleryRepository->findAll()
        ]);
    }

    /**
     * @Route("admin/supprime/image/{id}", name="gallery_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Gallery $image, Request $request){

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getTitle();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $this->entityManager->remove($image);
            $this->entityManager->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
