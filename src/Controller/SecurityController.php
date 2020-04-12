<?php

namespace App\Controller;

use App\Repository\UserRepository;
use http\Env\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,UserRepository $userRepository): Response
    {
        if($this->getUser())
            return new RedirectResponse($this->generateUrl('auction_userlist'));

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $nameOfUser = $userRepository->findName($lastUsername)["username"];

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'nameOfUser' => $nameOfUser]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
