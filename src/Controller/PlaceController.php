<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventType;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Query\GeocodeQuery;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends Controller
{

    /**
     * @Route("/create-events-place", name="create-events-place")
     */
    public function createEventPlace(Request $request, EntityManagerInterface $entityManager)
    {
        $cityRepo = $entityManager->getRepository(City::class);

        $place = new Place();

        if($request->isMethod('get')){


            $formData = $request->query->all();
            $btName = $request-> query -> get( 'submitPlace' );
            dump($btName);



            $place_label = $request -> query -> get("place_name");
            $city_num = $request -> query -> get("street_number");
            $city_street = $request -> query -> get("route");
            $city_name = $request -> query -> get("locality");
            $city_postcode = $request -> query -> get("postal_code");
            $city_address = $request -> query -> get("user_input_autocomplete_address");

            /*dump($city_num.' '.$city_street.' '.$city_name);*/

            if (!empty($place_label) && !empty($city_street) && !empty($city_name) && !empty($city_postcode)) {




                $place->setLabel($place_label);
                $place->setAddress($city_num.' '.$city_street);


                /* Check if the city is in database */

                $check_city = new City();
                $check_city = $cityRepo->findOneBy(array('name' => $city_name));

                if(!$check_city){
                    $city = new City();
                    $city -> setName($city_name);
                    $city -> setPostalCode($city_postcode);
                    $entityManager->persist($city);
                    $entityManager->flush();
                    $place->setCity($city);
                }else{
                    $place->setCity($check_city);
                }


                /* Define latitude and longitude */

                $config = [
                        'verify' => false,
                        'proxy'   => 'http://proxy-sh.ad.campus-eni.fr:8080',
                ];

                $guzzle = new GuzzleClient($config);

                $adapter  = new Client($guzzle);
                $provider = new GoogleMaps($adapter, null, 'AIzaSyBrRyTeCxvTBbznCTK8sfvzUEM4WeJEyg4' );
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
                $lat = $location->getLatitude();
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

//                return $this->redirectToRoute('create-events');
            }
            else if(!is_null($btName)) {
                $this->addFlash(
                        'danger',
                        'Lieu incorrect'
                );
            }

        }

        return new JsonResponse(
                [
                        "placeId"  => $place->getId(),
                        "placeLabel" => $place->getLabel(),
                ]
        );

//        $eventPlace     = new Place();
//        $eventPlaceForm = $this->createForm(PlaceType::class, $eventPlace);
//        $eventPlaceForm->handleRequest($request);
//
//        if ($eventPlaceForm->isSubmitted() & $eventPlaceForm->isValid()) {
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($eventPlace);
//            $entityManager->flush();
//
//        //   return $this->redirectToRoute('create-events');
//        }
//
//        return $this->render(
//                'events/createEvent.html.twig',
//            [
//                'eventPlaceForm' => $eventPlaceForm->createView(),
//            ]
//        );

    }

//
//    public function createEventPlace(Request $request)
//    {
//
//        $eventPlace     = new Place();
//        $eventPlaceForm = $this->createForm(PlaceType::class, $eventPlace);
//        $eventPlaceForm->handleRequest($request);
//
//        if ($eventPlaceForm->isSubmitted() & $eventPlaceForm->isValid()) {
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($eventPlace);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('create-events');
//        }
//
//        return $this->render(
//            'events/createEvent.html.twig',
//            [
//                'eventPlaceForm' => $eventPlaceForm->createView(),
//            ]
//        );
//
//    }


}
