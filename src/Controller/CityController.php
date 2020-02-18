<?php


namespace App\Controller;


use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Model\AdminLevelCollection;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\GoogleMaps\Model\GoogleAddress;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client;


class CityController extends Controller
{

    /**
     * @Route("/update_cities", name="update_cities")
     */
    public function city(Request $request, EntityManagerInterface $entityManager){

        $cityRep = $entityManager->getRepository(City::class);

        /* Searching cities */
        $var_search = $request->query->get("var_search");
        dump($var_search);

        $city_result = null;

        if(!is_null($var_search)){
            $city_result = $cityRep -> findBySearch($var_search);

            dump($city_result);
        }

        /* Add city */
        $city_name = $request->query->get("city");
        $city_postcode = $request->query->get("postcode");
        if(!is_null($city_name) && !is_null($city_postcode)){
            $city = new City();
            $city->setName($city_name);
            $city->setPostalCode($city_postcode);

            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Ville ajoutée'
            );
        }

        /* Remove city */
        $city_id_delete = $request->query->get("delete");
        if(!is_null($city_id_delete)){
            dump('id ville :'.$city_id_delete);
            $city_delete = $cityRep ->find($city_id_delete);
            $entityManager -> remove($city_delete);
            $entityManager -> flush();
            $this->addFlash(
                'success',
                'Ville supprimée'
            );
        }


        /* Update city */
        $city_id_update = $request->query->get("update");
        if(!is_null($city_id_update)){
            $city_name = $request->query->get("city_mod");
            $city_postcode = $request->query->get("postcode_mod");
            $city_update = new City();
            $city_update = $cityRep ->find($city_id_update);
            $city_update -> setName($city_name);
            $city_update -> setPostalCode($city_postcode);
            $entityManager -> persist($city_update);
            $entityManager -> flush();
            $this->addFlash(
                'success',
                'Ville Modifiée'
            );
        }

        return $this->render('city/update_cities.html.twig',compact('city_result'));
    }

    /**
     * @Route("/geo", name="geo")
     * @throws \Geocoder\Exception\Exception
     */
    public function test_geo(){


/*        $httpClient = new \Http\Adapter\Guzzle6\Client();
        $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient, null, 'AIzaSyBrRyTeCxvTBbznCTK8sfvzUEM4WeJEyg4');
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');*/


/*        $result = $geocoder->geocodeQuery(GeocodeQuery::create('Buckingham Palace, London'));*/

        $config = [
            'verify' => false,
            'proxy'   => 'http://proxy-sh.ad.campus-eni.fr:8080',
        ];

        $guzzle = new GuzzleClient($config);

        $adapter  = new Client($guzzle);
        $provider = new GoogleMaps($adapter, null, 'AIzaSyBrRyTeCxvTBbznCTK8sfvzUEM4WeJEyg4' );
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

        $result = $geocoder->geocodeQuery(GeocodeQuery::create('Buckingham Palace, London'));

        $coordinates = $result->all();

        dump($coordinates);

        $res = $result->first();
        dump($res);

        $location = $res->getCoordinates();
        dump($location);
        dump($location->getLatitude());
        dump($location->getLongitude());







        return $this->render('city/test_geo.html.twig');
    }

}