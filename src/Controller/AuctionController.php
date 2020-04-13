<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Bid;
use App\Form\AuctionType;
use App\Repository\AuctionRepository;
use App\Repository\BidRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/auction",methods={"GET","POST"})
 */
class AuctionController extends AbstractController
{

    /**
     * @Route("/", name="auction_index", methods={"GET"})
     * @isGranted("ROLE_STAFF")
     */
    public function index(AuctionRepository $auctionRepository): Response
    {
        if($this->getUser())
            $nameOfUser = $this->getUser()->getUsername();

        return $this->render('auction/index.html.twig', [
            'auctions' => $auctionRepository->findAll(),
            'nameOfUser' => $nameOfUser
        ]);
    }

//    /**
//     * @Route("/list", name="auction_list", methods={"GET"})
//     */
//    public function listAuctions(AuctionRepository $auctionRepository,StateRepository $stateRepository): Response
//    {
//
//        $template = 'auction/list.html.twig';
//        $args = [
//            'auctions' => $auctionRepository->findByExampleField($stateRepository->findLiveOne()),
//        ];
//
//        return $this->render($template,$args);
//    }

//    /**
//     * @Route("/userlist", name="auction_userlist", methods={"GET"})
//     * @isGranted ("ROLE_USER")
//     */
//    public function listUserAuctions(AuctionRepository $auctionRepository,
//                                     StateRepository $stateRepository,
//                                     BidRepository $bidRepository,
//                                     UserRepository $userRepository): Response
//    {
//
//        $template = 'auction/userList.html.twig';
//        $auctionUserList = $auctionRepository->findByExampleField($stateRepository->findLiveOne());
//        $uctionAllList = $auctionRepository->findAll();
//        $userID = $this->getUser()->getID();
//        $bidArray = [];
//        $winners = [];
//        $user = $this->getUser();
//
//        if($this->getUser())
//            $nameOfUser = $this->getUser()->getUsername();
//
//        foreach ($uctionAllList as $bidAuction) :
//
//            $bid = $bidRepository->findOneBySomeField($userID, $bidAuction->getID());
//            if ($bid && !in_array($bidAuction,$auctionUserList)) {
//
//                array_push($auctionUserList, $bidAuction);
//                $bidAuction->addBid($bid);
//            }
//        endforeach;
//
//
//
//
//        foreach ($auctionUserList as $userAuction):
//
//            $bids = $userAuction->getBids();
//            $bidTag = "no bid";
//
//            $winner = '';
//
//            foreach($bids as $bid) {
//                $bid = $bidRepository->findOneBySomeField($userID, $userAuction->getID());
//                if ($bid)
//                    $bidTag = "bidden";
//            }
//
//            if($userAuction->getWinner())
//                $winner = $userRepository->findUserFromID($userAuction->getWinner())->getUsername();
//
//            array_push($bidArray,$bidTag);
//            array_push($winners,$winner);
//
//        endforeach;
////var_dump($winners);
//        $args = [
//            'auctions' => $auctionUserList,
//            'bidArray' => $bidArray,
//            'winners' => $winners,
//            'nameOfUser' => $nameOfUser,
//            'user' => $user
//        ];
//
//        return $this->render($template,$args);
//    }

    /**
     * @Route("/new", name="auction_new", methods={"GET","POST"})
     * @isGranted("ROLE_STAFF")
     */
    public function new(Request $request): Response
    {
        $auction = new Auction();
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auction);
            $entityManager->flush();

            return $this->redirectToRoute('auction_index');
        }

        return $this->render('auction/new.html.twig', [
            'auction' => $auction,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="auction_show", methods={"GET"})
     * @isGranted("ROLE_STAFF")
     */
    public function show(Auction $auction): Response
    {
        return $this->render('auction/show.html.twig', [
            'auction' => $auction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="auction_edit", methods={"GET","POST"})
     * @isGranted("ROLE_STAFF")
     */
    public function edit(Request $request, Auction $auction): Response
    {
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('auction_index');
        }

        return $this->render('auction/edit.html.twig', [
            'auction' => $auction,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="auction_delete", methods={"DELETE"})
     * @isGranted("ROLE_STAFF")
     */
    public function delete(Request $request, Auction $auction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auction->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($auction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('auction_index');
    }

//    /**
//     * @Route("/bidOnAuction/{auctionID}", name="auction_bid")
//     */
//    public function bidOnAction(Request $request,BidRepository $bidRepository,AuctionRepository $auctionRepository){
//
//        //if($request->getMethod() == Request::METHOD_POST){
//           // $auctionID =$request->request->get('auctionID');
//            //var_dump($auctionID);
//            //$auctionID = $request->query->get('auctionID');
//            //var_dump($auctionID);
//            $auctionID = $request->get('auctionID');
//
//            $auction = $auctionRepository->findOneByID($auctionID);
//
//            $userID = $this->getUser();
//            $bid = $bidRepository->findOneBySomeField($userID,$auctionID);
//            if(!$bid) {
//                $bid = new Bid();
//                $bid->setUser($userID);
//                $bid->setAuction($auctionRepository->findOneByID($auctionID));
//                $bid->setAmmount(1);
//                $auction->setWinner($userID->getID());
//            }else{
//                $bid->setAmmount($bid->getAmmount()+1);
//                $bid->setUser($userID);
//                $auction->setWinner($userID->getID());
//            }
//            $this->getDoctrine()->getManager()->persist($bid);
//            $this->getDoctrine()->getManager()->flush();
//       // }
//
//        return $this->redirectToRoute('auction_userlist');
//    }
}
