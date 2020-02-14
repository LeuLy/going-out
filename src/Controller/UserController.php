<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\FileType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Uploadable\FileInfo\FileInfoArray;
use Gedmo\Uploadable\FileInfo\FileInfoInterface;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);



    }
    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/profilModif", name="profilModif")
     * @param UploadableManager $uploadableManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profilModif(UploadableManager $uploadableManager, Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);




        if($userForm->isSubmitted() && $userForm->isValid()) {

            $filedata = $userForm->get('file')->getData();

            dump($filedata);

            $file = new File();

            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            if ($filedata instanceof UploadedFile) {
               dump('ok');
               dump($filedata->getClientOriginalName());
               dump($filedata->getClientSize());
               dump($filedata->getClientMimeType());
               dump($filedata->getRealPath());
               dump($filedata->getClientOriginalExtension());

                $pathF = $filedata->move(
                    'public/uploads',
                    $filedata->getClientOriginalName()
                );
                dump($pathF);


               $inf = new UploadedFileInfo($filedata);
                dump($inf);

                $file->setMimeType($filedata->getClientMimeType());
                $file->setName($filedata->getClientOriginalName());
                $file->setSize($filedata->getClientSize());
                $file->setPath($filedata->getRealPath());
                $file->setUser($user);
                $file->setPublicPath('');

                $uploadableManager->markEntityToUpload($file, $inf, $pathF );
                dump('nom '.$file->getName());
                dump($user);

            }else{
                dump('not ok');
            }

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                'success',
                'Modification enregistrée'
            );
            return $this->render('user/profilModif.html.twig', ['userFormView' => $userForm->createView(), compact('file')]);
        }






        return $this->render('user/profilModif.html.twig', ['userFormView' => $userForm->createView()]);
    }
    /**
     * @Route("/affichProfil", name="affichProfil")
     */
    public function affichProfil()
    {
        return $this->render('user/affichProfil.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
