<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
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

}
