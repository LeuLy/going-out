<?php


namespace App\Controller;


use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends Controller
{

    /**
     * @Route("/update_sites", name="update_sites")
     */
    public function site(Request $request, EntityManagerInterface $entityManager){


        $siteRep = $entityManager -> getRepository(Site::class);

        /* Searching cities */
        $var_search = $request->query->get("var_search");
        dump($var_search);

        $site_result = null;

        if(!is_null($var_search)){
            $site_result = $siteRep -> findBySearch($var_search);

            dump($site_result);
        }

        return $this->render('site/update_sites.html.twig',compact('site_result'));

    }

}