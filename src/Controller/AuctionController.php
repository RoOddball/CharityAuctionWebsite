<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Bid;
use App\Form\AuctionType;
use App\Repository\AuctionRepository;
use App\Repository\BidRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;

/**
 * @Route("/auction")
 */
class AuctionController extends AbstractController
{
    /**
     * @Route("/", name="auction_index", methods={"GET"})
     */
    public function index(AuctionRepository $auctionRepository): Response
    {
        return $this->render('auction/index.html.twig', [
            'auctions' => $auctionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/list", name="auction_list", methods={"GET"})
     */
    public function listAuctions(AuctionRepository $auctionRepository,StateRepository $stateRepository): Response
    {

        $template = 'auction/list.html.twig';
        $args = [
            'auctions' => $auctionRepository->findByExampleField($stateRepository->findLiveOne())
        ];

        return $this->render($template,$args);
    }

    /**
     * @Route("/userlist", name="auction_userlist", methods={"GET"})
     */
    public function listUserAuctions(AuctionRepository $auctionRepository,StateRepository $stateRepository): Response
    {

        $template = 'auction/userList.html.twig';
        $args = [
            'auctions' => $auctionRepository->findByExampleField($stateRepository->findLiveOne())
        ];

        return $this->render($template,$args);
    }

    /**
     * @Route("/{auctionID}/bidOnAuction", name="auction_bid", methods={"POST","GET"})
     */
    public function bidOnAction(Request $request,BidRepository $bidRepository){

        if($request->getMethod() == Request::METHOD_POST){
            $auctionID =$request->request->get('auctionID');
            $userID = $this->getUser();
            $bid = $bidRepository->findOneBySomeField($userID,$auctionID);
            if(!$bid) {
                $bid = new Bid();
                $bid->setUser($userID);
                $bid->setAuction($auctionID);
                $bid->setAmmount(1);
            }else{
                $bid->setAmmount($bid->getAmmount()+1);
            }
            $this->getDoctrine()->getManager()->persist($bid);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('auction_userlist');
    }

    /**
     * @Route("/new", name="auction_new", methods={"GET","POST"})
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
     */
    public function show(Auction $auction): Response
    {
        return $this->render('auction/show.html.twig', [
            'auction' => $auction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="auction_edit", methods={"GET","POST"})
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
}
