<?php

namespace App\Controller;

use App\Entity\IsBid;
use App\Form\IsBidType;
use App\Repository\IsBidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/is/bid")
 */
class IsBidController extends AbstractController
{
    /**
     * @Route("/", name="is_bid_index", methods={"GET"})
     */
    public function index(IsBidRepository $isBidRepository): Response
    {
        return $this->render('is_bid/index.html.twig', [
            'is_bids' => $isBidRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="is_bid_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $isBid = new IsBid();
        $form = $this->createForm(IsBidType::class, $isBid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($isBid);
            $entityManager->flush();

            return $this->redirectToRoute('is_bid_index');
        }

        return $this->render('is_bid/new.html.twig', [
            'is_bid' => $isBid,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="is_bid_show", methods={"GET"})
     */
    public function show(IsBid $isBid): Response
    {
        return $this->render('is_bid/show.html.twig', [
            'is_bid' => $isBid,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="is_bid_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, IsBid $isBid): Response
    {
        $form = $this->createForm(IsBidType::class, $isBid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('is_bid_index');
        }

        return $this->render('is_bid/edit.html.twig', [
            'is_bid' => $isBid,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="is_bid_delete", methods={"DELETE"})
     */
    public function delete(Request $request, IsBid $isBid): Response
    {
        if ($this->isCsrfTokenValid('delete'.$isBid->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($isBid);
            $entityManager->flush();
        }

        return $this->redirectToRoute('is_bid_index');
    }
}
