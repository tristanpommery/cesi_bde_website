<?php

namespace App\Service\Cart;

use App\Entity\User;
use Twig\Environment;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (empty($cart[$id]) or $cart[$id] === 1) {
            $this->delete($id);
        } else {
            $cart[$id]--;
            $this->session->set('cart', $cart);
        }
    }

    public function delete(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function getFullCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartWithData = [];
        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $cartWithData;
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

    public function checkout(\Swift_Mailer $mailer, Environment $renderer, EntityManagerInterface $em)
    {
        $members = $em->getRepository(User::class)->findByRole('ROLE_BDE');
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
