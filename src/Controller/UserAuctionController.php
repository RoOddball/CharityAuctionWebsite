<?php
/**
 * Created by PhpStorm.
 * User: tarab
 * Date: 13/04/2020
 * Time: 12:37
 */

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
 * @Route("/bid",methods={"GET","POST"})
 */
class UserAuctionController extends AbstractController
{

    /**
     * @Route("/bidOnAuction/{auctionID}", name="auction_bid", methods={"GET","POST"})
     */
    public function bidOnAction(Request $request,BidRepository $bidRepository,AuctionRepository $auctionRepository){

        $auctionID = $request->get('auctionID');
        var_dump($auctionID);
        $auction = $auctionRepository->findOneByID($auctionID);

        $userID = $this->getUser();

        $bids = $bidRepository->findByAuction($auction);

        $bid = $bidRepository->findOneBySomeField($userID,$auctionID);
        if(!$bid) {
            $bid = new Bid();
            $bid->setUser($userID);
            $bid->setAuction($auctionRepository->findOneByID($auctionID));

            //findByAuction returns an array of bids assocated to an auction obj
            //ordered in descending order by amount so the first item in array
            //has the highest amount attribute always in the bids of an auction
            if(count($bids)>0)
                $bid->setAmmount($bidRepository->findByAuction($auction)[0]->getAmmount()+1);
            else
                $bid->setAmmount(1);
            $auction->setWinner($userID->getID());
        }else{
            $bid->setAmmount($bidRepository->findByAuction($auction)[0]->getAmmount()+1);
            $bid->setUser($userID);
            $auction->setWinner($userID->getID());
        }
        $this->getDoctrine()->getManager()->persist($bid);
        $this->getDoctrine()->getManager()->flush();
        // }

        return $this->redirectToRoute('auction_userlist');
    }

    /**
     * @Route("/list", name="auction_list", methods={"GET"})
     */
    public function listAuctions(AuctionRepository $auctionRepository,StateRepository $stateRepository): Response
    {

        $template = 'auction/list.html.twig';
        $args = [
            'auctions' => $auctionRepository->findByExampleField($stateRepository->findLiveOne()),
        ];

        return $this->render($template,$args);
    }


    /**
     * @Route("/userlist", name="auction_userlist", methods={"GET"})
     * @isGranted ("ROLE_USER")
     */
    public function listUserAuctions(AuctionRepository $auctionRepository,
                                     StateRepository $stateRepository,
                                     BidRepository $bidRepository,
                                     UserRepository $userRepository): Response
    {

        $template = 'auction/userList.html.twig';
        $auctionUserList = $auctionRepository->findByExampleField($stateRepository->findLiveOne());
        $uctionAllList = $auctionRepository->findAll();
        $userID = $this->getUser()->getID();
        $user = $this->getUser();
        $bidArray = [];
        $winners = [];
        $ammounts =[];

        if($this->getUser())
            $nameOfUser = $this->getUser()->getUsername();

        //for each of the auctions check if the current user made a bid
        //if so we add them to the array of auctions to be displayed
        //along with the live ones

        foreach ($uctionAllList as $bidAuction) :

            $bid = $bidRepository->findOneBySomeField($userID, $bidAuction->getID());
            if ($bid && !in_array($bidAuction,$auctionUserList)) {

                array_push($auctionUserList, $bidAuction);
                $bidAuction->addBid($bid);
            }
        endforeach;


        //for each of the auctions in the "diplay to user" array
        //make array for the your bids label
        //make array for winner label
        //make array for ammount label

        foreach ($auctionUserList as $userAuction):

            //get the bids of each auction obj in a var called bids

            $bids = $userAuction->getBids();
            $bidTag = "no bid";
            $winner = 'none' ;
            $ammount = 0;

            //if the array is not empty
            //the findByAuction method returns bids of an auction in descending order by amount
            //so we get the first item in the array which has already the highest amount attribute

            if(count($bids)>0)
                $ammount = $bidRepository->findByAuction($userAuction)[0]->getAmmount();

            //for each bid of an auction obj we check to see if they have this auction and user as
            //attributes and if so we make the your bid label to display bidden

            foreach($bids as $bid) {
                $bid = $bidRepository->findOneBySomeField($userID, $userAuction->getID());
                if ($bid) {
                    $bidTag = "bidden";
                }
            }

            //we get the username of the winner from the auctions winner attribute which is an int indicating a
            //user id

            if($userAuction->getWinner())
                $winner = $userRepository->findUserFromID($userAuction->getWinner())->getUsername();

            //we push all these items into arrays with indexae equal to the index of the auction obj
            // we wish to associate them with in the auctionUserList array

            array_push($bidArray,$bidTag);
            array_push($winners,$winner);
            array_push($ammounts,$ammount);

        endforeach;
//var_dump($winners);
        $args = [
            'auctions' => $auctionUserList,
            'bidArray' => $bidArray,
            'winners' => $winners,
            'ammounts' => $ammounts,
            'nameOfUser' => $nameOfUser,
            'user' => $user
        ];

        return $this->render($template,$args);
    }

    /**
     * @Route("/{id}", name="auction_visitorshow", methods={"GET"})
     */
    public function show(Auction $auction): Response
    {
        return $this->render('auction/visitorShow.html.twig', [
            'auction' => $auction,
        ]);
    }
}