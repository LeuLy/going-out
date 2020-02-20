<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Form\FileType;
use App\Form\UserModifType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Uploadable\FileInfo\FileInfoArray;
use Gedmo\Uploadable\FileInfo\FileInfoInterface;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
        return $this->render(
            'user/index.html.twig',
            [
                'controller_name' => 'UserController',
            ]
        );
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

        if (!is_null($error)) {
            $this->addFlash(
                    'danger',
                    'Erreur de connexion'
            );
        }

        return $this->render(
            'user/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );


    }

    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render(
            'user/register.html.twig',
            [
                'controller_name' => 'UserController',
            ]
        );
    }

    /**
     * @Route("/profilModif", name="profilModif")
     * @param   UploadableManager  $uploadableManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profilModif(
        UploadableManager $uploadableManager,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $user     = $this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);


        if ($userForm->isSubmitted() && $userForm->isValid()) {
            

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

                if (!empty($user->getFile())) {
                    $fileExists = new File();
                    $fileRep    = $entityManager->getRepository(File::class);
                    $fileExists = $fileRep->findOneBy(['user' => $user->getId()]);
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

                $uploadableManager->markEntityToUpload($file, $inf, $pathF);
                dump('nom '.$file->getName());
                dump($user);
                dump($file);
                $path = $file->getPublicPath();
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
        $userRepo    = $entityManager->getRepository(User::class);
        $user        = $userRepo->find($userId);
        $currentUser = $this->getUser();

        return $this->render(
            'user/affichProfil.html.twig',
            compact('user', 'currentUser')

        );
    }

    /**
     * @Route("/userModif/{userId}", name="userModif", requirements={"userId": "\d+"})
     * @param   UploadableManager  $uploadableManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userModif(
            UploadableManager $uploadableManager,
            Request $request,
            EntityManagerInterface $entityManager,
            UserPasswordEncoderInterface $passwordEncoder,
            $userId
    ) {
        $userRepo    = $entityManager->getRepository(User::class);
        $user        = $userRepo->find($userId);
        $currentUser = $this->getUser();

        $userForm = $this->createForm(UserModifType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                    'success',
                    'Modification enregistrée'
            );


            return $this->redirectToRoute('userProfile', ['userId' => $userId]);
        }

//        return $this->render(
//                'user/affichProfil.html.twig',
//                compact('user', 'currentUser')

//        );

        return $this->render('user/userModif.html.twig', ['userFormView' => $userForm->createView()]);
    }



    /**
     * @Route("/deleteUser/{userId}", name="deleteUser")
     */
    public function deleteUser($userId, EntityManagerInterface $entityManager)
    {

        $userRepository = $entityManager->getRepository(User::class);
        $user           = $userRepository->find($userId);
        $user->setActive(false);
        $user->setErased(true);

        $user->setRoles(['ROLE_DELETED']);


        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Utilisateur supprimé'
        );

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/deactivateUser/{userId}", name="deactivateUser")
     */
    public function deactivateUser($userId, EntityManagerInterface $entityManager)
    {

        $userRepository = $entityManager->getRepository(User::class);
        $user           = $userRepository->find($userId);
        $user->setActive(false);


        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Utilisateur désactivé'
        );

        return $this->redirectToRoute('userProfile', ['userId' => $userId]);
    }

    /**
     * @Route("/activateUser/{userId}", name="activateUser")
     */
    public function activateUser($userId, EntityManagerInterface $entityManager)
    {

        $userRepository = $entityManager->getRepository(User::class);
        $user           = $userRepository->find($userId);
        $user->setActive(true);


        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Utilisateur réactivé'
        );

        return $this->redirectToRoute('userProfile', ['userId' => $userId]);
    }


    /**
     * @Route("/affichProfil", name="affichProfil")
     */
    public function affichProfil()
    {

        $user  = $this->getUser();
        $photo = $user->getFile();
        dump($photo);

        return $this->render('user/affichProfil.html.twig', compact('photo'));
    }
}
