<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('main/home.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('main/about.html.twig');
    }

    /**
     * @Route("/event", name="allEvents")
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
     * @Route("/association", name="allAssociations")
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
}
