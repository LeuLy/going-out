<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, EntityManagerInterface $entityManager)
    {

        $siteRepository = $entityManager->getRepository(Site::class);

        $siteAll = $siteRepository -> findAll();
        dump($siteAll);
        $size = sizeof($siteAll);
        dump($size);



        return $this->render('main/home.html.twig', compact( 'siteAll','size'));
    }

}