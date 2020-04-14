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
}
