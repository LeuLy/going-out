<?php


namespace App\Controller;


use App\Entity\File;
use App\Entity\User;
use App\Form\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FileController  extends Controller
{

    public function profilModifFile(Request $request){

        $user = $this->getUser();

        $file = new File();
        $fileForm = $this->createForm(FileType::class,$file);
        $fileForm->handleRequest($request);

        if($fileForm->isSubmitted() & $fileForm->isValid()){


            /*$entityManager->persist($user);*/
            /*$entityManager->flush();*/
        }

        return $this->render(
            'user/profilModif.html.twig',
            [
                'fileForm' => $fileForm->createView(),
            ]
        );
    }



}