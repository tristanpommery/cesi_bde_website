<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Gallery;
use App\Entity\User;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;


class GalleryController extends AbstractController
{
    /**
     * @Route("/event/{id}/gallery", name="gallery_event")
     */
    public function eventGallery(Event $event, Request $request, ObjectManager $manager)
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form['image']->getData();
            $newFileName = "https://via.placeholder.com/1020x2000";
            if ($uploadedFile) {
                $imagePath = '/assets/pictures/gallery/';
                $destination = $this->getParameter('kernel.project_dir') . '/public/assets/pictures/gallery';
                $newFileName = $imagePath . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFileName
                );
            }
            $gallery->setImage($newFileName);
            $gallery->setEvent($event);
            $gallery->setAuthor($this->getUser());
            $manager->persist($gallery);
            $manager->flush();

            return $this->redirectToRoute('gallery_event', ['id' => $event->getId()]);
        }

        return $this->render('main/gallery/index.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event/{yeet}/gallery/{id}/remove", name="gallery_delete")
     */
    public function delete(Gallery $gallery, Request $request, ObjectManager $manager): Response
    {
        $event = $gallery->getEvent();
        if ($this->isCsrfTokenValid('delete' . $gallery->getId(), $request->request->get('_token'))) {
            $manager->remove($gallery);
            $manager->flush();
        }

        return $this->redirectToRoute('gallery_event', ['id' => $event->getId()]);
    }


    /**
     * @Route("/report/{id}", name="gallery_report")
     */
    public function report(Request $request, Event $event, \Swift_Mailer $mailer, Environment $renderer, EntityManagerInterface $em, UserInterface $user)
    {
        $members = $em->getRepository(User::class)->findByRole('ROLE_BDE');
        $content = $request->get('report_message');
        if ($members) {
            foreach ($members as $member) {
                $message = (new \Swift_Message('signalement image :' . $event->getName()))
                    ->setFrom($this->getUser()->getEmail())
                    ->setTo($member->getEmail())
                    ->setBody($renderer->render('main/event/report.html.twig', [
                        'content' => $content,
                        'event' => $event
                    ]),
                        'text/html'
                    );
                $mailer->send($message);

            }
        }
        return $this->redirectToRoute('gallery_event', ['id' => $event->getId()]);
    }

}
