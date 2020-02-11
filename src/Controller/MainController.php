<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('main/home.html.twig',);
    }

}

