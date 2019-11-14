<?php
namespace App\Service\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class CartService
{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id, Request $request)
    {

        $value = $request->cookies->get('cartCookie');
        $cart = $this->session->get('cart', []);

        if ($value !== null) {
            $cart = json_decode($value, true);
        }

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $cookie = new Cookie('cartCookie', json_encode($cart), (time() + 7 * 24 * 60 * 60));
        $this->session->set('cart', $cart);
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();
    }

    public function remove(int $id, Request $request)
    {
        $cart = $this->session->get('cart', []);
        $value = $request->cookies->get('cartCookie');
        if ($value !== null) {
            $cart = json_decode($value, true);
        }
        if (!empty($cart[$id])) {
            $cart[$id]--;
            if ($cart[$id] == 0) {
                unset($cart[$id]);
            }
        }
        $this->session->set('cart', $cart);
        $cookie = new Cookie('cartCookie', json_encode($cart), time() + (7 * 24 * 60 * 60));
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();
    }

    public function getFullCart(): array
    {
        if (isset($_COOKIE['cartCookie'])) {
            if ($_COOKIE['cartCookie'] !== null) {
                $cart = json_decode($_COOKIE['cartCookie']);
                $cartWithData = [];
                foreach ($cart as $id => $quantity) {

                    $cartWithData[] = [
                        'product' => $this->productRepository->find($id),
                        'quantity' => $quantity
                    ];
                }
                return $cartWithData;
            }
        }
        return $cartWithData = [];
    }

    public function getTotal(): float
    {
        $total = 0;
        $cartWithData = $this->getFullCart();
        dd($cartWithData);
        foreach ($cartWithData as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $total;
    }

    public function isInCart(Product $product): bool
    {
        $cartWithData = $this->getFullCart();

        foreach ($cartWithData as $item) {
            if ($item['product'] == $product) {
                return true;
            }
        }
        return false;
    }

    public function checkout(\Swift_Mailer $mailer, Environment $renderer, Request $request)
    {
        $message = (new \Swift_Message(
            'Récapitulatif de commande pour :'))
            ->setFrom('noreply@bde-cesi-st.fr')
            ->setTo('tristan.pommery@viacesi.fr')
            ->setBody(
                $renderer->render('cart/mail.html.twig', [
                    'items' => $cartWithData = $this->getFullCart(),
                    'total' => $total = $this->getTotal()
                ]),
                'text/html'
            );

       /* $cart = $this->session->get('cart', []);
        $value = $request->cookies->get('cartCookie');
        /*if ($value !== null){
            $cart = [];
            if ($cart == null){
                unset($cart);
            }
        }*/

       /* $this->session->set('cart', $cart);
        $cookie = new Cookie('cartCookie', json_encode($cart), time()+7*24*60*60);
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();
        $mailer->send($message);*/
    }
}

