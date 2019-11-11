<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(EventRepository $eventRepository)
    {
        return $this->render('main/home.html.twig', [
            'events' => $eventRepository->find3firsts(),
        ]);
    }

    /**
     * @Route("/event", name="all_events")
     */
    public function allEvent()
    {
        return $this->render('main/allEvent.html.twig');
    }

    /**
     * @Route("/event/{id}", name="event")
     */
    public function event($id)
    {
        return $this->render('main/event.html.twig', [
            'id'=>$id,
        ]);
    }

    /**
     * @Route("/user/{id}", name="user")
     */
    public function user($id)
    {
        return $this->render('main/user.html.twig', [
            'id'=>$id,
        ]);
    }

    /**
     * @Route("/association", name="all_associations")
     */
    public function allAssociations()
    {
        return $this->render('main/allAssociations.html.twig');
    }

    /**
     * @Route("/association/{id}", name="association")
     */
    public function association($id)
    {
        return $this->render('main/association.html.twig', [
            'id'=>$id,
        ]);
    }

    /**
     * @Route("/admin", name="admin_index")
     */
    public function adminIndex()
    {
        return $this->render('main/index.html.twig');

    }


}
