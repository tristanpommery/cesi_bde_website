<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\ProductRepository;
use App\Repository\AssociationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(EventRepository $eventRepository, AssociationRepository $associationRepository, ProductRepository $productRepository)
    {
        return $this->render('main/home.html.twig', [
            'events' => $eventRepository->find3firsts(),
            'associations' => $associationRepository->findAll(),
            'products' => $productRepository->find3firsts(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user")
     */
    public function user($id)
    {
        return $this->render('main/user.html.twig', [
            'id'=>$id,
            'firstname'=>$this->getUser()->getFirstName()
        ]);
    }

    /**
     * @Route("/legal", name="legal")
     */
    public function legal()
    {
        return $this->render('main/legal.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, EventRepository $eventRepo, ProductRepository $productRepo, AssociationRepository $AssociationRepo)
    {
        $keyword = $request->get('keyword');
        $events = array();
        $products = array();
        $associations = array();

        if ($keyword) {
            $events = $eventRepo->findByKeyWord($keyword);
            $products = $productRepo->findByKeyWord($keyword);
            $associations = $AssociationRepo->findByKeyWord($keyword);
        }

        $eventsCount = count($events);
        $productsCount = count($products);
        $associationsCount = count($associations);

        return $this->render('main/search.html.twig', [
            'keyword' => $keyword,
            'events' => $events,
            'eventsCount' => $eventsCount,
            'products' => $products,
            'productsCount' => $productsCount,
            'associations' => $associations,
            'associationsCount' => $associationsCount,
        ]);
    }
}
