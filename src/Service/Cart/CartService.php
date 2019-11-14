<?php
namespace App\Service\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session=$session;
        $this->productRepository=$productRepository;
    }

    public function add(int $id)
    {

        //$value = $request->cookies->get('cart');
        $cart = $this->session->get('cart', []);

       /* if ($value !== null) {
            $cart = json_decode($value, true);
        }*/

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        //$cookie = new Cookie('cart', json_encode($cart), (time() + 365 * 24 * 60 * 60));
        $this->session->set('cart', $cart);

        /*$res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();*/
    }

    public function remove(int $id){
        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id])){
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function getFullCart() : array {
        $cart = $this->session->get('cart', []);
        $cartWithData= [];
        foreach ($cart as $id => $quantity) {

            $cartWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];

        }
        return $cartWithData;
    }

    public function getTotal() : float {
        $total=0;
        $cartWithData=$this->getFullCart();

        foreach ($cartWithData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $total;
    }

    public function isInCart(Product $product) : bool {
        $cartWithData=$this->getFullCart();

        foreach ($cartWithData as $item){
            if($item['product']==$product){
                return true;
            }
        }
        return false;
    }

}

