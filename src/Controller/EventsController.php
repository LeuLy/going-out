<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Site;
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
            $formData = $request->request->all();
            dump($formData);



            if (empty($formData['event']['place'])) {

                $place = new Place();

                $placeData = $formData['event']['placeForm'];
                dump($formData);
                dump($placeData);

                $place->setLabel($formData['event']['placeForm']['label']);
                $place->setAddress($formData['event']['placeForm']['address']);
                $place->setLatitude($formData['event']['placeForm']['latitude']);
                $place->setLongitude($formData['event']['placeForm']['longitude']);
                dump($place);
                $entityManager->persist($place);
                $entityManager->flush();
                $event->setPlace($place);
            }

//            $testPlace = null;
//            $testPlace = $formData['event']['place'];
//            dump($testPlace);

//            if (empty($formData['event']['place'])) {
//
//                $place = new Place();
//
//                $placeData = $formData['event']['placeForm'];
//                dump($formData);
//                dump($placeData);
//
//                $place->setLabel($formData['event']['placeForm']['label']);
//                $place->setAddress($formData['event']['placeForm']['address']);
//                $place->setLatitude($formData['event']['placeForm']['latitude']);
//                $place->setLongitude($formData['event']['placeForm']['longitude']);
//                dump($place);
//                $entityManager->persist($place);
//                $entityManager->flush();
//                $event->setPlace($place);
//            }


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
     * @Route("/event/{page}", name="listEvent", requirements={"page": "\d+"})
     */
    public function event(Request $request, EntityManagerInterface $entityManager, $page = 0)
    {
        $limit          = 1;
        $siteRepository = $entityManager->getRepository(Site::class);


        $eventRepository = $entityManager->getRepository(Event::class);

//        $var                = $request->query->get("var");
//        $eventByDescription = $eventRepository->findEventByDescription($var, $page, $limit);



        $siteLabel = $request->query->get("label");
        $site      = $siteRepository->findByLabel($siteLabel);
        $event     = $eventRepository->findEventBySite($site, $page, $limit);

        $nbTotalPictures = count($event);

        $nbPage = ceil($nbTotalPictures / $limit);


        return $this->render('events/event.html.twig', compact('event', 'page', 'nbPage', 'site', 'siteLabel'));
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

