<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginFormType;
use App\Form\UserRegistrationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @param Request             $request
     * @param AuthenticationUtils $authUtils
     *
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error        = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        $loginForm    = $this->createForm(UserLoginFormType::class);

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            'loginForm'     => $loginForm->createView(),
        ]);
    }

    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request)
    {
        $registrationForm = $this->createForm(UserRegistrationFormType::class);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $em   = $this->getDoctrine()->getManager();
            $data = $registrationForm->getData();
            $user = new User();
            $user
                ->setUsername($data['username'])
                ->setEmail($data['email'])
                ->setPlainPassword($data['password']);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        // ['registrationForm' => $registrationForm] === compact($registrationForm)
        return $this->render('security/register.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }
}
