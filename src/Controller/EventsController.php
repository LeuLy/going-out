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


        $entityManager = $this->getDoctrine()->getManager();
        $eventUser     = $entityManager->getRepository(Event::class);
        $event         = $eventUser->find($event_id);
        dump($event);

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
//        $event->setCreator($this->getUser());

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

        $event     = new Event();
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
        $event->setCreator($this->getUser());

        if ($eventForm->isSubmitted() & $eventForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $workflow = $this->workflows->get($event, 'eventStatus');
            dump($workflow);
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Sortie créée'
            );

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
     *
     * @Route("/event/{page}", name="listEvent", requirements={"page": "\d+"})
     */
    public function event(Request $request, EntityManagerInterface $entityManager, $page = 0)
    {
        $limit                 = 4;
        $siteRepository        = $entityManager->getRepository(Site::class);
        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
        $eventRepository       = $entityManager->getRepository(Event::class);
        $userRepository        = $entityManager->getRepository(User::class);

        $var           = $request->query->get("var");
        $beginDate     = $request->query->get("beginDate");
        $endDate       = $request->query->get("endDate");
        $passedEvent   = $request->query->get("passedEvent");
        $subscribed    = $request->query->get("subscribed");
        $notSubscribed = $request->query->get("notSubscribed");
        $eventOwner    = $request->query->get("eventOwner");
        $userId        = $this->getUser()->getId();


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


        $nbTotalEvents = count($event);
        $nbPage        = ceil($nbTotalEvents / $limit);


        //eventByCreator -> les evenements créés par l'utilisateur courant.
        $eventByCreator = $eventRepository->findEventByCreator($userId);


        $nbTotalEventsByDescription = count($eventByDescription);
        $nbPageByDescription        = ceil($nbTotalEventsByDescription / $limit);


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
                'userId'
            ) // userId  rajouté
        );
    }


    /**
     * @Route("/affichEvent/{id}", name="affichEvent")
     */
    public function affichEvent($id, Request $request, EntityManagerInterface $entityManager)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $event           = $eventRepository->find($id);

        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
        $inscriptions          = $inscriptionRepository->findSubscribedByEvent($id);

// WORKFLOW EN CREATION:
        $workflow = $this->workflows->get($event, 'eventStatus');
//        $workflow->apply($event, 'eventPublish');

        dump($workflow);
        dump($inscriptions);

//        if ($workflow->can($event, 'eventPublish')) {

//            $eventWorkflow = new event();
//            $workflow = $this->get('workflow.registry')->get($eventWorkflow);
//            $workflow->getMarking($eventWorkflow);
//
//            $workflow->apply($eventWorkflow, 'eventPublish');
//        }

        $place = $event->getPlace();


        return $this->render('events/affichEvent.html.twig', compact('event', 'inscriptions', 'place'));
    }


}










