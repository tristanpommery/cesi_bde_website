<?php

namespace App\Service\Cart;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class CartService
{

    private $session;
    private $productRepository;
    private $manager;

    public function __construct(SessionInterface $session, ProductRepository $productRepository, ObjectManager $manager)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->manager = $manager;
    }

    public function add(int $id, Request $request)
    {

        $value = $request->cookies->get('cartCookie');
        $cart = $this->session->get('cart', []);
        $product = $this->productRepository->find($id);

        if ($value !== null) {
            $cart = json_decode($value, true);
        }

        if (empty($cart[$id])) {
            $cart[$id] = 1;
        } elseif ($cart[$id] < $product->getStock()) {
            $cart[$id]++;
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

        $cookie = new Cookie('cartCookie', json_encode($cart), time() + (7 * 24 * 60 * 60));
        $this->session->set('cart', $cart);
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

    public function checkout(\Swift_Mailer $mailer, Environment $renderer, EntityManagerInterface $em, UserInterface $user)
    {
        $members = $em->getRepository(User::class)->findByRole('ROLE_BDE');
        $datas = $this->getFullCart();
        foreach($datas as $data) {
            $product = $data['product'];
            $quantity = $data['quantity'];
            $product->setStock($product->getStock() - $quantity);
            $product->setSoldCount($product->getSoldCount() + $quantity); 
            $this->manager->persist($product);
        }
        $this->manager->flush();

        if ($members) {
            foreach ($members as $member) {
                $message = (new \Swift_Message('Récapitulatif commande'))
                    ->setFrom('noreply@bde-cesi-st.fr')
                    ->setTo($member->getEmail())
                    ->setBody(
                        $renderer->render('cart/mail.html.twig', [
                            'items' => $cartWithData = $this->getFullCart(),
                            'total' => $total = $this->getTotal()
                        ]),
                        'text/html'
                    );
                $mailer->send($message);
            }
        }
        unset($_COOKIE['cartCookie']);
        setcookie('cartCookie', null, -1, '/');
    }
}
