<?php

namespace App\Controller;

use App\Repository\AuctionRepository;
use App\Repository\BidRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ResetType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @isGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @isGranted("ROLE_ADMIN")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/reset", name="user_reset", methods={"GET","POST"})
     * @isGranted ("ROLE_USER")
     */
    public function reset(Request $request,User $user): Response
    {
        //$user = $this->getUser();
        $form = $this->createForm(ResetType::class, $user);
        $form->handleRequest($request);

        $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));
        //$user->setRoles($this->getUser()->getRoles());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('welcome');
        }

        //$user->getPassword();

        return $this->render('user/reset.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user,
                           AuctionRepository $auctionRepository,
                           BidRepository $bidRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            $bids = $bidRepository->findByUser($user);

            $entityManager = $this->getDoctrine()->getManager();

            //remove bids associated with user to be deleted
            //and set winner for associated auction to null

            foreach($bids as $bid):
                $bid->getAuction()->setWinner(null);
                $entityManager->remove($bid);
            endforeach;

            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
