<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/profilModif", name="profilModif")
     */
    public function profilModif()
    {
        return $this->render('user/profilModif.html.twig', [
            'controller_name' => 'UserController',
        ]);
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
