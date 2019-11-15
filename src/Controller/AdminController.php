<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function users()
    {
        return $this->render('admin/users.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function dashboard()
    {
        return $this->render('admin/dashboard.html.twig', [
        ]);
    }
}
