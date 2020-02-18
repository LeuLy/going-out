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

        /* Show all */
        $site_result = $siteRep ->findAll();

        /* Searching cities */
        $var_search = $request->query->get("var_search");
        dump($var_search);

        if(!is_null($var_search)){
            $site_result = $siteRep -> findBySearch($var_search);

            dump($site_result);
        }

        /* Add site */
        $site_name = $request->query->get("label");
        if(!is_null($site_name)){
            $site = new Site();
            $site->setLabel('ENI '.strtoupper($site_name));

            $entityManager->persist($site);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Site ajouté'
            );
        }

        /* Remove site */
        $site_id_delete = $request->query->get("delete");
        if(!is_null($site_id_delete)){
            $site_delete = $siteRep ->find($site_id_delete);
            $entityManager -> remove($site_delete);
            $entityManager -> flush();
            $this->addFlash(
                'success',
                'Site supprimé'
            );
        }

        /* Update site */
        $site_id_update = $request->query->get("update");
        if(!is_null($site_id_update)){
            $site_name = $request->query->get("site_mod");
            $site_update = new Site();
            $site_update = $siteRep ->find($site_id_update);
            $site_update -> setLabel(strtoupper($site_name));
            $entityManager -> persist($site_update);
            $entityManager -> flush();
            $this->addFlash(
                'success',
                'Site Modifié'
            );
        }


        return $this->render('site/update_sites.html.twig',compact('site_result'));

    }

}