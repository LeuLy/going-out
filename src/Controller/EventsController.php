<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Inscription;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\User;
use App\Form\EventType;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Query\GeocodeQuery;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class EventsController extends Controller
{


    /**
     * @var Registry
     */
    private $workflows;

    public function __construct(Registry $workflows)
    {
        $this->workflows = $workflows;
    }


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
     * @Route("/update-event/{event_id}", name="update-event")
     */
    public function updateEvent($event_id = 0, Request $request, EntityManagerInterface $entityManager)
    {
//        $entityManager = $this->getDoctrine()->getManager();
        $eventUser = $entityManager->getRepository(Event::class);
        $event = $eventUser->find($event_id);

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
//        $event->setCreator($this->getUser());

        if ($eventForm->isSubmitted() & $eventForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $formData = $request->request->all();
//            dump($formData);

            if (empty($formData['event']['place'])) {

                $place = new Place();

                $placeData = $formData['event']['placeForm'];
//                dump($formData);
//                dump($placeData);

                $place->setLabel($formData['event']['placeForm']['label']);
                $place->setAddress($formData['event']['placeForm']['address']);

                $place->setLatitude($formData['event']['placeForm']['latitude']);
                $place->setLongitude($formData['event']['placeForm']['longitude']);
//                dump($place);
                $entityManager->persist($place);
                $entityManager->flush();
                $event->setPlace($place);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Sortie modifiée'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'events/updateEvent.html.twig',
            [
                'eventForm' => $eventForm->createView(),
            ]
        );
    }


    /**
     * @Route("/create-events", name="create-events")
     * @throws \Geocoder\Exception\Exception
     */
    public function createEvent(Request $request, EntityManagerInterface $entityManager)
    {
        $cityRepo = $entityManager->getRepository(City::class);

        $event = new Event();
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
        $event->setCreator($this->getUser());


        $workflow = $this->workflows->get($event, 'eventStatus');
        try {
            $workflow->apply($event, 'newEvent');
        } catch (LogicException $exception) {

        }
        $transitions = $workflow->getEnabledTransitions($event);
//        dump($transitions);
//        dump($event);

// Update the currentState on the post

        if ($eventForm->isSubmitted() & $eventForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Sortie créée'
            );

            return $this->redirectToRoute('create-events');
        }


//        dump($event);

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


        $limit = 4;
        $siteRepository = $entityManager->getRepository(Site::class);
        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
        $eventRepository = $entityManager->getRepository(Event::class);
        $userRepository = $entityManager->getRepository(User::class);

        $var = $request->query->get("var");
        $beginDate = $request->query->get("beginDate");
        $endDate = $request->query->get("endDate");
        $passedEvent = $request->query->get("passedEvent");
        $subscribed = $request->query->get("subscribed");
        $notSubscribed = $request->query->get("notSubscribed");
        $eventOwner = $request->query->get("eventOwner");
        $userId = $this->getUser()->getId();
        $ouverture = $request->query->get("ouverture");

        $user = $userRepository->find($this->getUser());

        $inscription = $inscriptionRepository->findAll();

//        Nombre total d'inscriptions
//        $nbTotalSubscribed = count ($inscription);
//        dump($nbTotalSubscribed);

//        $nbInscription = $inscription->getInscriptions()->count();

        $siteLabel = $request->query->get('label');

        $site = $siteRepository->findByLabel($siteLabel);

        $event = $eventRepository->findEventBySite($site, $page, $limit);

        $eventByDescription = $eventRepository->findEventByFilters(
            $beginDate,
            $endDate,
            $eventOwner,
            $userId,
            $user,
            $passedEvent,
            $subscribed,
            $notSubscribed,
            $var,
            $site,
            $page,
            $limit


        );




//
//        $now = date('Y-m-d h:i:s',strtotime("now"));
//
//
//        $now1 = date(strtotime("now"));
//        $eventClosed = $eventRepository->findNowEvent($now);
//        if ($eventClosed) {
//
//            foreach ($eventClosed as $archive1) {
//                $eventDuration=$archive1->getDuration($archive1);
//                $value = $now1 - $eventDuration;
//                $eventDurationEnd = (date('Y-m-d h:i:s',("$value")));
//
//                $archiveDuration= $eventRepository->findDurationEnd($eventDurationEnd);
//                foreach ($archiveDuration as $archiveDurationValue) {
//                    $archiveState = $archiveDurationValue->getState();
//                    if ($archiveState == 'ActiviteEnCours') {
//                        $archiveDurationValue->setState('Passee');
//
//                        $entityManager->persist($archiveDurationValue);
//                        $entityManager->flush();
//                    }
//
//                }
//            }
//        }
//        $eventNow = $eventRepository->findNowEvent($now);
//
//
//        if ($eventNow) {
//
//            foreach ($eventNow as $archive) {
//
//                $archiveState = $archive->getState();
//                if ($archiveState == 'Ouverte') {
//                    $archive->setState('ActiviteEnCours');
//
//                    $entityManager->persist($archive);
//                    $entityManager->flush();
//                }
//            }
//        }




//        $eventBefore = $eventRepository->findBeforeEvent($now);

//        if ($eventBefore) {
//
//            foreach ($eventBefore as $archive) {
//
//                $archiveState = $archive->getState();
//                if ($archiveState =! 'Ouverte'){
//                $archive->setState('EnCreation');
//
//                $entityManager->persist($archive);
//                $entityManager->flush();
//            }
//            }
//        }




        $lastMonth = date('Y-m-d h:i:s', strtotime("last month"));
        $eventArchived = $eventRepository->findArchivedEvent($lastMonth);
        if ($eventArchived) {

            foreach ($eventArchived as $archive) {



                $archive->setState('Archivee');
                $entityManager->persist($archive);
                $entityManager->flush();

            }
        }



        $nbTotalEvents = count($event);
        $nbPage = ceil($nbTotalEvents / $limit);


        //eventByCreator -> les evenements créés par l'utilisateur courant.
        $eventByCreator = $eventRepository->findEventByCreator($userId);


        $nbTotalEventsByDescription = count($eventByDescription);
        $nbPageByDescription = ceil($nbTotalEventsByDescription / $limit);


        return $this->render(
            'events/event.html.twig',
            compact(
                'eventByDescription',
                'eventByCreator',
                'page',
                'user',
                'nbPageByDescription',
                'nbPage',
                'siteLabel',
                'event',
                'inscription',
                'limit',
                'var',
                'beginDate',
                'endDate',
                'passedEvent',
                'eventOwner',
                'subscribed',
                'notSubscribed',
                'userId',
                'eventArchived'
            ) // userId  rajouté
        );
    }




//
//    /**
//     * @Route("/archivedEvent/{id}", name="archivedEvent")
//     */
//    public function archivedEvent($id, Request $request, EntityManagerInterface $entityManager)
//    {
//        $eventRepository = $entityManager->getRepository(Event::class);
//        $event = $eventRepository->find($id);
//
//        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
//        $inscriptions = $inscriptionRepository->findSubscribedByEvent($id);
//
//
//        $workflow = $this->workflows->get($event, 'eventStatus');
//        try {
//            $workflow->apply($event, 'eventPublish');
//            $entityManager->persist($event);
//            $entityManager->flush();
//        } catch (LogicException $exception) {
//
//        }
//        $transitions = $workflow->getEnabledTransitions($event);
//
//        $place = $event->getPlace();
////        dump($workflow);
//        dump($event);
//
//
//        return $this->render(
//            'events/event.html.twig',
//            compact(
//                'eventByDescription',
//                'eventByCreator',
//                'page',
//                'user',
//                'nbPageByDescription',
//                'nbPage',
//                'siteLabel',
//                'event',
//                'inscription',
//                'limit',
//                'var',
//                'beginDate',
//                'endDate',
//                'passedEvent',
//                'eventOwner',
//                'subscribed',
//                'notSubscribed',
//                'userId'
//            ) // userId  rajouté
//        );    }
//
//
//
//
//
//


    /**
     * @Route("/ouvertureEvent/{id}", name="ouvertureEvent")
     */
    public function ouvertureEvent($id, Request $request, EntityManagerInterface $entityManager)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $event = $eventRepository->find($id);


        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
        $inscriptions = $inscriptionRepository->findSubscribedByEvent($id);


        $workflow = $this->workflows->get($event, 'eventStatus');
        try {
            $workflow->apply($event, 'eventPublish');
            $entityManager->persist($event);
            $entityManager->flush();
        } catch (LogicException $exception) {

        }
        $transitions = $workflow->getEnabledTransitions($event);

        $place = $event->getPlace();

        $this->addFlash(
                'success',
                'Sortie ouverte à l\'inscription'
        );


        return $this->render('events/affichEvent.html.twig', compact('event', 'inscriptions', 'place'));
    }


    /**
     * @Route("/affichEvent/{id}", name="affichEvent")
     */
    public function affichEvent($id, Request $request, EntityManagerInterface $entityManager)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $event = $eventRepository->find($id);

        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
        $inscriptions = $inscriptionRepository->findSubscribedByEvent($id);



        $place = $event->getPlace();



        return $this->render('events/affichEvent.html.twig', compact('event', 'inscriptions', 'place'));
    }


    /**
     * @Route("/cancelEvent/{id}", name="cancelEvent", requirements={"id": "\d+"})
     */
    public function cancelEvent($id, Request $request, EntityManagerInterface $entityManager)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $event = $eventRepository->find($id);

        $eventCancelTxt = $request->query->get("cancelTxt");


            if ($eventCancelTxt != null) {


                $workflow = $this->workflows->get($event, 'eventStatus');

//                $workflow = $this->workflows->get($event);

                try {
                    $workflow->apply($event, 'cancelEvent');
                    $entityManager->persist($event);
                    $entityManager->flush();
                } catch (LogicException $exception) {
                }

                $transitions = $workflow->getEnabledTransitions($event);




                $event->setCancelTxt($eventCancelTxt);

                $entityManager->persist($event);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Sortie annulée'
                );

                return $this->redirectToRoute('home');
            }


        return $this->render('events/cancelEvent.html.twig', compact('event'));
    }



    /**
     * @Route("/eventAllSites/{page}", name="listEventAllSites", requirements={"page": "\d+"})
     */
    public function eventAllSites(Request $request, EntityManagerInterface $entityManager, $page = 0)
    {
        $limit = 4;
        $siteRepository = $entityManager->getRepository(Site::class);
        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
        $eventRepository = $entityManager->getRepository(Event::class);
        $userRepository = $entityManager->getRepository(User::class);

        $var = $request->query->get("var");
        $beginDate = $request->query->get("beginDate");
        $endDate = $request->query->get("endDate");
        $passedEvent = $request->query->get("passedEvent");
        $subscribed = $request->query->get("subscribed");
        $notSubscribed = $request->query->get("notSubscribed");
        $eventOwner = $request->query->get("eventOwner");
        $userId = $this->getUser()->getId();


        $user = $userRepository->find($this->getUser());

        $inscription = $inscriptionRepository->findAll();


        $event = $eventRepository->findAll();

        $eventByDescription = $eventRepository->findEventByFiltersAllSites(
            $beginDate,
            $endDate,
            $eventOwner,
            $userId,
            $user,
            $passedEvent,
            $subscribed,
            $notSubscribed,
            $var,
            $page,
            $limit
        );

        $nbTotalEvents = count($event);
        $nbPage = ceil($nbTotalEvents / $limit);


        //eventByCreator -> les evenements créés par l'utilisateur courant.
        $eventByCreator = $eventRepository->findEventByCreator($userId);


        $nbTotalEventsByDescription = count($eventByDescription);
        $nbPageByDescription = ceil($nbTotalEventsByDescription / $limit);


        return $this->render(
            'events/eventAllSites.html.twig',
            compact(
                'eventByDescription',
                'eventByCreator',
                'page',
                'user',
                'nbPageByDescription',

                'nbPage',
                'event',
                'inscription',
                'limit',
                'var',
                'beginDate',
                'endDate',
                'passedEvent',
                'eventOwner',
                'subscribed',
                'notSubscribed',
                'userId'
            )
        );
    }

}










