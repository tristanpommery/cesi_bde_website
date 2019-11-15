<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;


class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('main/event/index.html.twig', [
            'events' => $eventRepository->findAllOrderedByDate(),
        ]);
    }

    /**
     * @Route("/event/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form['image']->getData();
            if($uploadedFile){
                $imagePath = '/assets/pictures/events/';
                $destination = $this->getParameter('kernel.project_dir').'/public/assets/pictures/events';
                $newFileName = $imagePath.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,$newFileName
                );
                $event->setImage($newFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('main/event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        $user = $this->getUser();
        if ($event->getUsers()->contains($user)) {
            $isParticipating = true;
        } else {
            $isParticipating = false;
        }

        $participatingCount = count($event->getUsers());

        return $this->render('main/event/show.html.twig', [
            'event' => $event,
            'isParticipating' => $isParticipating,
            'participatingCount' => $participatingCount,
        ]);
    }

    /**
     * @Route ("/event/apply/{id}", name="event_apply")
     */
    public function add(Event $event, ObjectManager $manager): Response
    {
        $user = $this->getUser();
        if($user) {
            $user->addEvent($event);
            $manager->persist($user);
            $manager->flush();
        }

        return $this->redirectToRoute('event_show', ['id'=>$event->getId()]);
    }

    /**
     * @Route("/event/disapply/{id}", name="event_disapply")
     */
    public function remove(Event $event, ObjectManager $manager): Response
    {
        $user = $this->getUser();
        if($user) {
            $user->removeEvent($event);
            $manager->persist($user);
            $manager->flush();
        }

        return $this->redirectToRoute('event_show', ['id'=>$event->getId()]);
    }

    /**
     * @Route("/event/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
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
            $event->setImage($newFileName);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('main/event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event/{id}", name="event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/event/report/{id}", name="event_report")
     */
    public function report(Request $request, Event $event, \Swift_Mailer $mailer, Environment $renderer, EntityManagerInterface $em, UserInterface $user)
        {
            $members = $em->getRepository(User::class)->findByRole('ROLE_BDE');
            $content = $request->get('report_message');
            if ($members) {
                foreach ($members as $member){
                    $message = (new \Swift_Message('signalement évènement :'.$event->getName()))
                        ->setFrom($this->getUser()->getEmail())
                        ->setTo($member->getEmail())
                        ->setBody($renderer->render('main/event/report.html.twig', [
                            'content'=>$content,
                            'event'=>$event
                        ]),
                            'text/html'
                        );
                    $mailer->send($message);

                }
            }
        return $this->redirectToRoute('event_index');
    }
}
