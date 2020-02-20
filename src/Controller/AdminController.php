<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserModifType;
use Doctrine\ORM\EntityManagerInterface;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render(
                'admin/index.html.twig',
                [
                        'controller_name' => 'AdminController',
                ]
        );
    }


    /**
     * @Route("/userModif/{userId}", name="userModif", requirements={"userId": "\d+"})
     * @param UploadableManager $uploadableManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userModif(
            UploadableManager $uploadableManager,
            Request $request,
            EntityManagerInterface $entityManager,
            UserPasswordEncoderInterface $passwordEncoder,
            $userId
    ) {
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->find($userId);
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
        $user = $userRepository->find($userId);
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
        $user = $userRepository->find($userId);
        $user->setActive(false);

        $user->setRoles(['ROLE_DEACTIVATED']);


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
        $user = $userRepository->find($userId);
        $user->setActive(true);

        $user->setRoles(['ROLE_USER']);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
                'success',
                'Utilisateur réactivé'
        );

        return $this->redirectToRoute('userProfile', ['userId' => $userId]);
    }


}
