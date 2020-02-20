<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserNewPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/fpw", name="fpw")
 */
class ForgottenPasswordController extends Controller
{


    /**
     * @Route("/forgotten/password", name="forgotten_password")
     */
    public function index()
    {
        return $this->render(
                'forgotten_password/index.html.twig',
                [
                        'controller_name' => 'ForgottenPasswordController',
                ]
        );
    }


    /**
     * @Route("/newPassword/{email}/{token}", name="newPassword", requirements={"email": "[a-z0-9@.-]{1,200}", "token": "[a-f0-9]{1,200}"})
     */
    public function newPassword(
            Request $request,
            EntityManagerInterface $entityManager,
            UserPasswordEncoderInterface $passwordEncoder,
            $email
    ) {
        $userRepo = $entityManager->getRepository(User::class);

        $user = $userRepo->findUserByUsernameOrEmail($email);

        $userForm = $this->createForm(UserNewPasswordType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $user->setPassword(
                    $passwordEncoder->encodePassword(
                            $user,
                            $userForm->get('password')->getData()
                    )
            );

            $user->setPasswordToken(null);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                    'success',
                    'Modification enregistrée'
            );

            return $this->redirectToRoute('login');
        }

        return $this->render('forgotten_password/newPassword.html.twig', ['userFormView' => $userForm->createView()]);
    }


    /**
     * @Route("/passwordToken", name="passwordToken")
     */
    public function forgottenPasswordToken(Request $request, EntityManagerInterface $entityManager)
    {
        $userRepo = $entityManager->getRepository(User::class);

        $userInfo = $request->query->get("info_user_fpw");
        $userToken = $request->query->get("token_user_fpw");

        if (($userInfo != null) && ($userToken != null)) {
            $user = $userRepo->findUserByUsernameOrEmail($userInfo);

            if ($user) {
                if ($user->getPasswordToken() == $userToken) {
                    $userEmail = $user->getEmail();
                    $userToken = $user->getPasswordToken();
                    return $this->redirectToRoute('fpwnewPassword', ['email' => $userEmail, 'token' => $userToken]);
                } else {
                    $this->addFlash(
                            'danger',
                            'Clé incorrecte'
                    );
                }
            } else {
                $this->addFlash(
                        'danger',
                        'Identifiant ou email incorrect'
                );
            }
        }

        return $this->render('forgotten_password/passwordToken.html.twig');
    }


    /**
     * @Route("/forgottenpassword", name="forgottenPassword")
     * @throws \Exception
     */
    public function forgottenPassword(Request $request, EntityManagerInterface $entityManager)
    {
        $userRepo = $entityManager->getRepository(User::class);

        $userInfo = $request->query->get("info_user_fpw");

        if ($userInfo != null) {
            $user = $userRepo->findUserByUsernameOrEmail($userInfo);

            if ($user) {
                $token = bin2hex(random_bytes(10));
                $user->setPasswordToken($token);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                        'success',
                        'Votre clé vous a été envoyée par email! [ => '.$token.']'
                );

                return $this->redirectToRoute('fpwpasswordToken');
            } else {
                $this->addFlash(
                        'danger',
                        'Identifiant ou email incorrect'
                );
            }
        }

        return $this->render('forgotten_password/forgottenPassword.html.twig');
    }

}
