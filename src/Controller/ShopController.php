<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Service\ImageUploader;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;

/**
 * @Route ("/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/", name="shop", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('main/product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile= $form['image']->getData();
            $newFileName="https://via.placeholder.com/1020x2000";
            if($uploadedFile){
                $imagePath = '/assets/pictures/products/';
                $destination = $this->getParameter('kernel.project_dir').'/public/assets/pictures/products';
                $newFileName = $imagePath.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,$newFileName
                );
            }
            $product->setImage($newFileName);
            $product->setsoldCount(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('shop');
        }

        return $this->render('main/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product, CartService $cartService): Response
    {
        $isInCart = $cartService->isInCart($product);
        return $this->render('main/product/show.html.twig', [
            'product' => $product,
            'isInCart' => $isInCart,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile= $form['image']->getData();
            $newFileName="https://via.placeholder.com/1020x2000";
            if($uploadedFile){
                $imagePath = '/assets/pictures/products/';
                $destination = $this->getParameter('kernel.project_dir').'/public/assets/pictures/products';
                $newFileName = $imagePath.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,$newFileName
                );
            }
            $product->setImage($newFileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shop');
        }

        return $this->render('main/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('shop');
    }
}
