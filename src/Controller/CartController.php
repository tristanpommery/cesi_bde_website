<?php

namespace App\Controller;

use Twig\Environment;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartService $cartService)
    {
        return $this->render('cart/index.html.twig', [
            'items'=> $cartWithData = $cartService->getFullCart(),
            'total'=>$total=$cartService->getTotal()
        ]);
    }

    /**
     * @Route ("cart/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cartService) {
        $cartService->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route ("cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService)
    {
        $cartService->remove($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
    public function delete($id, CartService $cartService)
    {
        $cartService->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/checkout", name="checkout")
     */
    public function checkout(CartService $cartService, \Swift_Mailer $mailer, Environment $renderer, EntityManagerInterface $em)
    {
        $cartService->checkout($mailer, $renderer, $em);

        return $this->render('cart/confirmation.html.twig');
    }
}