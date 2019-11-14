<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

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
    public function add($id, CartService $cartService){
        $cartService->add($id);
        return $this->redirectToRoute('product_show', ['id'=>$id]);
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id,CartService $cartService)
    {
        $cartService->remove($id);
        return $this->redirectToRoute('product_show', ['id'=>$id]);
    }

    /**
     * @Route("/cart/persist", name="cart_persist")
     */
    public function persist(SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart', []);
        if(($request->cookies->get('cart')) !== null)
        {
            if($cart == null)
            {
                dd("test");
            }
        }
        $DataCart = "";
        foreach ($cart as $id => $quantity) {
            $DataCart = $DataCart . $id . "-" . $quantity . ";";
        }
        $cookie = new Cookie(
            'cart',
            $DataCart,
            time() + ( 1 * 7 * 24 * 60 * 60)
        );
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/get", name="cart_get")
     */
    public function getCart(SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart', []);
        if(($request->cookies->get('cart')) !== null)
        {
            if($cart == null)
            {
                $savedCart = ($request->cookies->get('cart'));
                $products = explode(';', $savedCart);

                foreach ($products as $id => $product)
                {
                    if($product !== "")
                    {
                        $temp = explode('-', $product);
                        for($i = 0; $i < $temp[1]; $i++)
                        {
                            if(!empty($cart[$temp[0]]))
                            {
                                $cart[$temp[0]]++;
                            } else
                            {
                                $cart[$temp[0]] = 1;
                            }
                        }
                    }
                }
                $session->set('cart', $cart);
            }
        }
        return $this->redirectToRoute('cart');
    }


}