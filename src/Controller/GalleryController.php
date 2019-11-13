<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gallery")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("/", name="gallery_index", methods={"GET"})
     */
    public function index(GalleryRepository $galleryRepository): Response
    {
        return $this->render('main/gallery/index.html.twig', [
            'galleries' => $galleryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gallery_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile= $form['image']->getData();
            $newFileName="https://via.placeholder.com/1020x2000";
            if($uploadedFile){
                $imagePath = '/assets/pictures/gallery/';
                $destination = $this->getParameter('kernel.project_dir').'/public/assets/pictures/gallery';
                $newFileName = $imagePath.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,$newFileName
                );
            }
            $gallery->setImage($newFileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gallery);
            $entityManager->flush();

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('main/gallery/new.html.twig', [
            'gallery' => $gallery,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gallery_show", methods={"GET"})
     */
    public function show(Gallery $gallery): Response
    {
        return $this->render('main/gallery/show.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gallery_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gallery $gallery): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile= $form['image']->getData();
            $newFileName="https://via.placeholder.com/1020x2000";
            if($uploadedFile){
                $imagePath = '/assets/pictures/products/';
                $destination = $this->getParameter('kernel.project_dir').'/public/assets/pictures/products';
                $newFileName = $imagePath.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,$newFileName
                );
            }
            $gallery->setImage($newFileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('main/gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gallery_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Gallery $gallery): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gallery->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gallery_index');
    }
}
