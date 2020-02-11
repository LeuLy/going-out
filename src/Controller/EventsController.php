<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventType;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends Controller
{
    /**
     * @Route("/events", name="events")
     */
    public function index()
    {
        return $this->render(
            'events/index.html.twig',
            [
                'controller_name' => 'EventsController',
            ]
        );
    }




    /**
     * @Route("/create-events", name="create-events")
     */
    public function createEvent(Request $request)
    {

        $event     = new Event();
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
        $event->setCreator($this->getUser());

        if ($eventForm->isSubmitted() & $eventForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('create-events');
        }

        return $this->render(
            'events/createEvent.html.twig',
            [
                'eventForm' => $eventForm->createView(),
            ]
        );

    }

    /**
     * @Route("/event/{page}", name="event", requirements={"page": "\d+"})
     */
    public function home(Request $request, EntityManagerInterface $entityManager, $page = 0)
    {
        $limit = 5;

        $eventRepository = $entityManager->getRepository(Event::class);
        $site            = $request->query->get("label");

        $event = $eventRepository->findEventBySite($site, $page, $limit);

        $nbTotalPictures = count($event);

        $nbPage = ceil($nbTotalPictures / $limit);


        return $this->render('events/event.html.twig', compact('event', 'page', 'nbPage', 'site'));
    }




//    /**
//     * @Route("/create-events", name="create-events")
//     */
//    public function createEvent(Request $request)
//    {
//
//        $eventPlace     = new Place();
//        $eventPlaceForm = $this->createForm(PlaceType::class, $eventPlace);
//        $event     = new Event();
//        $eventForm = $this->createForm(EventType::class, $event);
//        $eventPlaceForm->handleRequest($request);
//        $eventForm->handleRequest($request);
//        $event->setCreator($this->getUser());
//
//        if ($eventForm->isSubmitted() & $eventForm->isValid()
//            & $eventPlaceForm->isSubmitted() & $eventPlaceForm->isValid()) {
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($eventPlace);
////            $entityManager->flush();
//            $entityManager->persist($event);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('create-events');
//        }
//
//
//
//        return $this->render(
//            'events/createEvent.html.twig',
//            [
//                'eventForm' => $eventForm->createView(),
//            ]
//        );
//
//    }




















//    /**
//     * @Route("/create-events", name="create-events")
//     */
//    public function createEvent(Request $request)
//    {
//
//        $eventPlace     = new Place();
//        $eventPlaceForm = $this->createForm(PlaceType::class, $eventPlace);
//        $event     = new Event();
//        $eventForm = $this->createForm(EventType::class, $event);
//        $eventPlaceForm->handleRequest($request);
//        $event->setCreator($this->getUser());
//
//        if ($eventForm->isSubmitted() & $eventForm->isValid()
//                & $eventPlaceForm->isSubmitted() & $eventPlaceForm->isValid()) {
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($eventPlace);
//            $entityManager->flush();
//            $entityManager->persist($event);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('create-events');
//        }
//
//
//
//        return $this->render(
//            'events/createEvent.html.twig',
//            [
//                'eventForm' => $eventForm->createView(),
//            ]
//        );
//
//    }

}

