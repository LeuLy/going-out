<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use App\Form\FichierCsvType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Serializer\Encoder\CsvEncoder;
//use Symfony\Component\Serializer\Encoder\JsonEncoder;
//use Symfony\Component\Serializer\Encoder\XmlEncoder;
//use Symfony\Component\Serializer\Exception\ExceptionInterface;
//use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
//use Symfony\Component\Serializer\Serializer;

class ParticipantController extends AbstractController
{

    /**
     * @Route("/enregistrerFichier", name ="enregistrerFichier")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function extraireFichierCsv(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
//        $user = new User();

        $form = $this->createForm(FichierCsvType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochure = $form->get('fichierCsv')->getViewData();

            $handle = fopen($brochure, "r");
            while (($data = fgetcsv($handle, 100, ',')) !== false) {
                $user = new User();

                $repo = $entityManager->getRepository(User::class);
                $userBDD = $repo->findOneBy([
                    'username' => trim($data[0])
                ]);

                if (trim($data[0]) != 'username' && $userBDD == null) {
                    $user->setUsername(trim($data[0]));
                    $user->setPassword(trim($data[1]));
                    $user->setName(trim($data[2]));
                    $user->setFirstname(trim($data[3]));
                    $user->setPhone(trim($data[4]));
                    $user->setEmail(trim($data[5]));
                    $user->setInscriptionYear((int)trim($data[6]));

                    dump($user);

                    // recherche site en BDD
                    $repo = $entityManager->getRepository(Site::class);
                    $siteBDD = $repo->findOneBy([
                        'label' => $data[7]
                    ]);

                    if ($siteBDD != null) {
                        $user->setSite($siteBDD);
                    } else {
                        $newSite = new Site();
//                        $newSite->setNom(trim($data[6]));
//                        $entityManager->persist($newSite);
//                        $user->setSite($newSite);
                    }

                    dump($user);

                    $user->setPassword(
                            $passwordEncoder->encodePassword(
                                    $user,
                                    $user->getPassword()
                            )
                    );

                    $user->setActive(true);

                    $entityManager->persist($user);

                    $this->addFlash("success", "Nouvel utilisateur inscrit");

                } else {
                    if ($data[0] != 'username') {
                        $this->addFlash("danger", " $data[0] déja inscrit!");
                    }
                }
            }
        }
        $entityManager->flush();


        return $this->render('user/csv.html.twig', ['formView' => $form->createView()]);
    }


}

