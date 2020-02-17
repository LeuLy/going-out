<?php


namespace App\Controller;


use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends Controller
{

    /**
     * @Route("/update_cities", name="update_cities")
     */
    public function city(Request $request, EntityManagerInterface $entityManager){

        $cityRep = $entityManager->getRepository(City::class);

        $var_search = $request->query->get("var_search");
        dump($var_search);

        $city_result = $cityRep -> findBySearch($var_search);

        dump($city_result);

        return $this->render('city/update_cities.html.twig',compact('city_result'));
    }

}