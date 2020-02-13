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
            $formData      = $request->request->all();
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

        $var       = $request->query->get("var");
        $beginDate = $request->query->get("beginDate");
        $endDate   = $request->query->get("endDate");


        $passedEvent = $request->query->get("passedEvent");
        dump($passedEvent);


        $eventOwner = $request->query->get("eventOwner");
        $userId     = $this->getUser()->getId();
        dump($eventOwner);

        dump($userId);

        dump($var);

        $siteLabel = $request->query->get('label');
        dump($siteLabel);
        $site     = $siteRepository->findByLabel($siteLabel);
        $siteKeep = $site;
        dump($site);

//        $event = [];
//        $sites = [$site];
//        foreach ($sites as $eventBySite) {
//            $event[$eventBySite] = $eventRepository->findEventBySite($eventBySite, $page, $limit);
//        }
        $event = $eventRepository->findEventBySite($site, $page, $limit);
        dump($event);
        $nbTotalEvents = count($event);

        $nbPage = ceil($nbTotalEvents / $limit);

        dump($site);
        $eventByDescription = $eventRepository->findEventByFilters(
            $beginDate,
            $endDate,
            $eventOwner,
            $userId,
            $passedEvent,
            $var,
            $site,
            $page,
            $limit
        );
        dump($siteKeep);
        dump($eventByDescription);
        $nbTotalEventsByDescription = count($eventByDescription);

        $nbPageByDescription = ceil($nbTotalEventsByDescription / $limit);


        return $this->render(
            'events/event.html.twig',
            compact('eventByDescription', 'page', 'nbPageByDescription', 'nbPage', 'siteLabel', 'event', 'limit', 'var')
        );
    }




    /**
     * @Route("/affichEvent/{id}", name="affichEvent")
     */
    public function affichEvent($id, Request $request,EntityManagerInterface $entityManager)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $event = $eventRepository->find($id);


        return $this->render('events/affichEvent.html.twig', compact('event'));
    }




}










