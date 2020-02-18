<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setName(strtoupper($user->getName()));
            $user->setFirstname(ucwords($user->getFirstname()));

//            $user->setUsername(strtolower($user->getName()).".".strtolower($user->getFirstname()));
            $user->setUsername(strtolower($user->getFirstname()[0]).strtolower($user->getName()).$user->getInscriptionYear());
            $user->setUsername(str_replace(' ', '',$user->getUsername()));
            $user->setPassword(strtolower($user->getFirstname()).".".strtolower($user->getName()));
            $user->setPassword(str_replace(' ', '',$user->getPassword()));
            $user->setActive(true);
            $user->setEmail(strtolower($user->getFirstname()) .".". strtolower($user->getName()).$user->getInscriptionYear().'@campus-eni.fr');
            $user->setEmail(str_replace(' ', '',$user->getEmail()));

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
//                    $form->get('plainPassword')->getData()
                )
            );

            if (
//                  (($user->getName() == 'Laz') && ($user->getFirstname() == 'Baptiste')) ||
//                  (($user->getName() == 'Lecomte') && ($user->getFirstname() == 'Anne-Laure')) ||
//                  (($user->getName() == 'Cornu') && ($user->getFirstname() == 'Lydia'))
                ((strcasecmp($user->getName(), 'Laz') == 0) && (strcasecmp($user->getFirstname(), 'Baptiste') == 0)) ||
                ((strcasecmp($user->getName(), 'Lecomte') == 0) && (strcasecmp(
                            $user->getFirstname(),
                            'Anne-Laure'
                        ) == 0)) ||
                ((strcasecmp($user->getName(), 'Cornu') == 0) && (strcasecmp($user->getFirstname(), 'Lydia') == 0))
            ) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $this->addFlash(
                'success',
                'Nouvel utilisateur enregistré'
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_register');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }
}
