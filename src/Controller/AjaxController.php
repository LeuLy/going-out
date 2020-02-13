<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxController extends AbstractController
{
    /**
     * @Route("/api/inscriptionEvent/{id}", name="ajax_route_inscriptionEvent")
     */
    public function inscriptionEvent($id=0, Request $request, EntityManagerInterface $entityManager)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $event           = $eventRepository->find($id);
        dump($event);

        $entityManager = $this->getDoctrine()->getManager();
        $eventUser     = new EventUser();
        dump($eventUser);



        $eventUser->setUserId($this->getUser()->getId());
        dump($eventUser);

        $eventUser->setEventId($event->getId());


        dump($eventUser);

        $entityManager->persist($eventUser);
        $entityManager->flush();


        return new JsonResponse(
            [
                "UserId"  => $eventUser->getUserId(),
                "EventId" => $eventUser->getEventId(),
            ]
        );
    }
}