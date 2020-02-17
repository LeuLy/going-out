<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\EventUser;
use App\Entity\Inscription;
use App\Entity\User;
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
    public function inscriptionEvent($id = 0, Request $request, EntityManagerInterface $entityManager)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $event           = $eventRepository->find($id);


        $entityManager = $this->getDoctrine()->getManager();
        $eventUser     = new Inscription();


        $eventUser->setEvent($event);


        $userRepository = $entityManager->getRepository(User::class);
        $user           = $userRepository->find($this->getUser());

        $eventUser->setUser($user);
//        dump($eventUser);


        $entityManager->persist($eventUser);
        $entityManager->flush();


        return new JsonResponse(
            [
                "UserId"  => $eventUser->getUser(),
                "EventId" => $eventUser->getEvent(),
            ]
        );
    }


    /**
     * @Route("/api/withdrawEvent/{event_id}", name="ajax_route_withdrawEvent")
     */
    public function withdrawEvent($event_id = 0, EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $eventUser     = $entityManager->getRepository(Event::class);
        $event         = $eventUser->find($event_id);

//        dump($event);
//        if (!$eventUser) {
//            throw $this->createNotFoundException(
//                'No user found for id '.$id
//            );
//        }

        $userRepository = $entityManager->getRepository(User::class);
        $user           = $userRepository->find($this->getUser());

//        dump($user);


        $InscriptionRepository = $entityManager->getRepository(Inscription::class);
        $inscription           = $InscriptionRepository->findOneBy(['user' => $this->getUser(), 'event' => $event]);
        dump($inscription);


        $entityManager->remove($inscription);
        $entityManager->flush();


        return new JsonResponse(
            [

//                "EventId" => $eventUser->getEventId(),
            ]
        );


    }

    /**
     * @Route("/api/deleteEvent/{event_id}", name="ajax_route_deleteEvent")
     */
    public function deleteEvent($event_id = 0, EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $eventUser     = $entityManager->getRepository(Event::class);
        $event         = $eventUser->find($event_id);



        $entityManager->remove($event);
        $entityManager->flush();


        return new JsonResponse(
                [


                ]
        );


    }

    /**
     * @Route("/api/seekCity/{event_id}", name="ajax_route_seekCity")
     */
    public function seekCity($var, Request $request, EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $cityRepo     = $entityManager->getRepository(City::class);
        $var = $request->query->get('city');
        $list = $cityRepo->findCityByVar($var);


        $entityManager->flush();


        return new JsonResponse(
                [


                ]
        );


    }

}


