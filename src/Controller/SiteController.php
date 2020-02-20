<?php


namespace App\Controller;


use App\Entity\Site;
use App\Form\SiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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


        $siteNew =new Site();
        $siteForm = $this->createForm(SiteType::class, $siteNew);
        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {

            dump('ok');

            /* Add site */
            $site_name = $siteForm->get('label')->getData();
            if(!is_null($site_name)){
                dump('ok ok');
                /*$site = new Site();*/
                $siteNew->setLabel('ENI '.strtoupper($site_name));
            }

            $imageFile = $siteForm->get('image')->getData();

            if ($imageFile instanceof UploadedFile) {
                dump('up');
            }
            if ($imageFile) {
                dump('ok image');
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('public/uploads_site'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $siteNew->setImage($newFilename);

                $entityManager->persist($siteNew);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Site ajouté'
                );
            }
            return $this->render('site/update_sites.html.twig', array('site_result'=>$site_result,'siteFormView' => $siteForm->createView()));
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


        return $this->render('site/update_sites.html.twig', array('site_result'=>$site_result,'siteFormView' => $siteForm->createView()));

    }

}