<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\FileType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

            $file = new File();
            $fileForm = $this->createForm(FileType::class, $file);
            $fileForm->handleRequest($request);
            /*  $file->getPath() instanceof \Symfony\Component\HttpFoundation\File\UploadedFile;*/
            $uploadableManager->markEntityToUpload($file, $file->getPath());

            $entityManager->persist($user);
            $entityManager->flush();

/*            if ($user->getFile()->getPath() instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
                $uploadManager = $this->get('stof_doctrine_extensions.uploadable.manager');
                $uploadManager->markEntityToUpload($user->getFile(), $user->getFile()->getPath());
            }*/

            $this->addFlash(
                'success',
                'Modification enregistrée'
            );
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
