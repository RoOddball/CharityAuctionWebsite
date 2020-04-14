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
 * @Route("/self")
 * isGranted(ROLE_USER)
 */
class SelfUserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/{id}/reset", name="user_reset", methods={"GET","POST"})
     */
    public function reset(Request $request,User $user,UserRepository $userRepository): Response
    {
        //$user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $error = null;

        $emailPlaceHolder = $user->getEmail();
        $usernamePlaceHolder = $user->getUsername();

        //$user->setRoles($this->getUser()->getRoles());
        //
        //
        if ($request->getMethod() == Request::METHOD_POST) {
            $currentUsername = $this->getUser()->getUsername();
            $user->setUsername($request->get('username'));
            $user->setPassword($this->passwordEncoder->encodePassword($user,$request->get('password')));
            $user->setEmail($request->get('email'));
            $usernameExists = $userRepository->findUserFromUsername($request->get('username'));
            if($currentUsername==$request->get('username')) {
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('auction_list');
            }else if(!$usernameExists){
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('auction_list');
            }else
                $error = 'username not available';
        }
        var_dump($user);
        //$user->getPassword();

        return $this->render('user/reset.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'emailPlaceHolder' => $emailPlaceHolder,
            'usernamePlaceHolder' => $usernamePlaceHolder,
            'error' => $error
        ]);
    }

    /**
     * @Route("/{id}", name="selfuser_delete", methods={"DELETE"})
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
            return $this->redirectToRoute('welcome');
        }



    }
}
