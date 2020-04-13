<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use http\Env\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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

    /**
     * @Route("/register", name="app_register")
     */
    public function register(AuthenticationUtils $authenticationUtils,UserRepository $userRepository,Request $request): Response
    {
        $error = null;
        if($this->getUser())
            return new RedirectResponse($this->generateUrl('auction_userlist'));

        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if($userRepository->findUserFromUsername($username))
            $error = 'credentials already in use';
        else
            if($request->getMethod() == Request::METHOD_POST)
            {
                $user = new User();
                $user->setRoles(["ROLE_USER"]);
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setPassword($this->passwordEncoder->encodePassword($user,$password));
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('auction_list');
            }

        return $this->render('security/register.html.twig', ['error'=>$error]);
    }
}
