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

        $currentUser = $this->getUser();
        if($currentUser->getErased() == true){
            $this->addFlash(
                    'danger',
                    'Ce compte a été supprimé'
            );
            return $this->redirectToRoute('logout');
        }


        $siteRepository = $entityManager->getRepository(Site::class);


        $eventRepository = $entityManager->getRepository(Event::class);


        $siteLabel = $request->query->get("label");
        $site      = $siteRepository->findByLabel($siteLabel);
        $event     = $eventRepository->findEventBySite($site);


        return $this->render('main/home.html.twig', compact('event', 'site', 'siteLabel'));
    }
}

