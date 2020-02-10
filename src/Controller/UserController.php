<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
    public function login()
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',
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
