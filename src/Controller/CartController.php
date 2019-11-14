<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\Cart\CartService;
use App\Service\Mailer\MailerService;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Entity\User;

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
    public function add($id, CartService $cartService, Request $request){


        $cartService->add($id, $request);
        return $this->redirectToRoute('product_show', ['id'=>$id]);
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id,CartService $cartService, Request $request)
    {
        $cartService->remove($id,$request);;

        return $this->redirectToRoute('shop');

    }


    /**
     * @Route("/cart/checkout", name="checkout")
     */
    public function checkout(CartService $cartService,\Swift_Mailer $mailer, Request $request, Environment $renderer)
    {
        $cartService->checkout( $mailer, $renderer, $request);

        return $this->render('cart/confirmation.html.twig');
    }






}