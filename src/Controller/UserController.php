<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
    public function profilModif(UploadableManager $uploadableManager, Request $request,
                                EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);



        if($userForm->isSubmitted() && $userForm->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $userForm->get('password')->getData()
                )
            );

            $filedata = $userForm->get('file')->getData();

            dump($filedata);

            $file = new File();

            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            if ($filedata instanceof UploadedFile) {

                if(!empty($user->getFile())){
                    $fileExists = new File();
                    dump(' not empty !!!');
                    $fileRep = $entityManager->getRepository(File::class);
                    $fileExists = $fileRep->findOneBy(['user'=>$user->getId()]);
                    $entityManager->remove($fileExists);
                    $entityManager->flush();
                }

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
                    dump($file);
                    $path = $file ->getPublicPath();
                    dump($path);

            }

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                'success',
                'Modification enregistrée'
            );
            return $this->render('user/profilModif.html.twig', ['userFormView' => $userForm->createView()]);
        }






        return $this->render('user/profilModif.html.twig', ['userFormView' => $userForm->createView()]);
    }


    /**
     * @Route("/userProfile/{userId}", name="userProfile", requirements={"userId": "\d+"})
     */
    public function userProfile(EntityManagerInterface $entityManager, $userId)
    {
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->find($userId);

        return $this->render('user/affichProfil.html.twig',
                compact('user')
        );
    }





    /**
     * @Route("/affichProfil", name="affichProfil")
     */
    public function affichProfil()
    {

        $user = $this->getUser();
        $photo = $user -> getFile();
        dump($photo);

        return $this->render('user/affichProfil.html.twig', compact('photo'));
    }
}
