<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventType;
use App\Form\PlaceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends Controller
{
    /**
     * @Route("/create-events-place", name="create-events-place")
     */
    public function createEventPlace(Request $request)
    {

        $eventPlace     = new Place();
        $eventPlaceForm = $this->createForm(PlaceType::class, $eventPlace);
        $eventPlaceForm->handleRequest($request);

        if ($eventPlaceForm->isSubmitted() & $eventPlaceForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventPlace);
            $entityManager->flush();

            return $this->redirectToRoute('create-events');
        }

        return $this->render(
            'events/createEvent.html.twig',
            [
                'eventPlaceForm' => $eventPlaceForm->createView(),
            ]
        );

    }

}
