<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Gallery;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;


class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/comment/new", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/comment/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }



    /**
     * @Route("/event/{yeet}/gallery/{id}", name="gallery_show")
     */
    public function gallery(Gallery $gallery, Request $request, ObjectManager $manager) {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTime());
            $comment->setGallery($gallery);
            $manager->persist($comment);
            $manager->flush();
        }
        return $this->render('main/gallery/show.html.twig', [
            "gallery" => $gallery,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/report/{id}", name="comment_report")
     */
    public function report(Request $request, Comment $comment, Event $event, \Swift_Mailer $mailer, Environment $renderer, EntityManagerInterface $em, UserInterface $user)
    {
        $members = $em->getRepository(User::class)->findByRole('ROLE_BDE');
        $content = $request->get('report_message');
        if ($members) {
            foreach ($members as $member) {
                $message = (new \Swift_Message('signalement image :' . $event->getName()))
                    ->setFrom($this->getUser()->getEmail())
                    ->setTo($member->getEmail())
                    ->setBody($renderer->render('main/comment/report.html.twig', [
                        'content' => $content,
                        'comment' => $comment
                    ]),
                        'text/html'
                    );
                $mailer->send($message);

            }
        }
        return $this->redirectToRoute('gallery_event', ['id' => $event->getId()]);
    }
}


