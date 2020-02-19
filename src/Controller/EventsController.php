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

        if ($request->isMethod('get')) {


            $formData = $request->query->all();
            $btName   = $request->query->get('submitPlace');
            dump($btName);


            $place_label   = $request->query->get("place_name");
            $city_num      = $request->query->get("street_number");
            $city_street   = $request->query->get("route");
            $city_name     = $request->query->get("locality");
            $city_postcode = $request->query->get("postal_code");
            $city_address  = $request->query->get("user_input_autocomplete_address");

            /*dump($city_num.' '.$city_street.' '.$city_name);*/

            if (!empty($place_label) && !empty($city_street) && !empty($city_name) && !empty($city_postcode)) {


                $place = new Place();

                $place->setLabel($place_label);
                $place->setAddress($city_num.' '.$city_street);


                /* Check if the city is in database */

                $check_city = new City();
                $check_city = $cityRepo->findOneBy(array('name' => $city_name));

                if (!$check_city) {
                    $city = new City();
                    $city->setName($city_name);
                    $city->setPostalCode($city_postcode);
                    $entityManager->persist($city);
                    $entityManager->flush();
                    $place->setCity($city);
                } else {
                    $place->setCity($check_city);
                }


                /* Define latitude and longitude */

                $config = [
                    'verify' => false,
                    'proxy'  => 'http://proxy-sh.ad.campus-eni.fr:8080',
                ];

                $guzzle = new GuzzleClient($config);

                $adapter  = new Client($guzzle);
                $provider = new GoogleMaps($adapter, null, 'AIzaSyBrRyTeCxvTBbznCTK8sfvzUEM4WeJEyg4');
                $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

                $adress = $city_address;
                $result = $geocoder->geocodeQuery(GeocodeQuery::create($adress));

                $coordinates = $result->all();

                /*dump($coordinates);*/

                $res = $result->first();
                /*dump($res);*/

                $location = $res->getCoordinates();
                /*                dump($location);
                                dump($location->getLatitude());
                                dump($location->getLongitude());*/
                $lat  = $location->getLatitude();
                $long = $location->getLongitude();

                $place->setLatitude($lat);
                $place->setLongitude($long);


                /*dump($place);*/
                $entityManager->persist($place);
                $entityManager->flush();
                /*$event->setPlace($place);*/
                $this->addFlash(
                    'success',
                    'Lieu créé'
                );

                return $this->redirectToRoute('create-events');
            } elseif (!is_null($btName)) {
                $this->addFlash(
                    'danger',
                    'Lieu incorrect'
                );
            }

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










