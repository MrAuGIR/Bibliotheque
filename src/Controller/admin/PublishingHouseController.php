<?php

namespace App\Controller\Admin;

use App\Entity\PublishingHouse;
use App\Form\PublishingHouseType;
use App\Repository\PublishingHouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publishing/house")
 */
class PublishingHouseController extends AbstractController
{
    /**
     * @Route("/", name="publishing_house_index", methods={"GET"})
     */
    public function index(PublishingHouseRepository $publishingHouseRepository): Response
    {
        return $this->render('publishing_house/index.html.twig', [
            'publishing_houses' => $publishingHouseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="publishing_house_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $publishingHouse = new PublishingHouse();
        $form = $this->createForm(PublishingHouseType::class, $publishingHouse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publishingHouse);
            $entityManager->flush();

            return $this->redirectToRoute('publishing_house_index');
        }

        return $this->render('publishing_house/new.html.twig', [
            'publishing_house' => $publishingHouse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publishing_house_show", methods={"GET"})
     */
    public function show(PublishingHouse $publishingHouse): Response
    {
        return $this->render('publishing_house/show.html.twig', [
            'publishing_house' => $publishingHouse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="publishing_house_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PublishingHouse $publishingHouse): Response
    {
        $form = $this->createForm(PublishingHouseType::class, $publishingHouse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('publishing_house_index');
        }

        return $this->render('publishing_house/edit.html.twig', [
            'publishing_house' => $publishingHouse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publishing_house_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PublishingHouse $publishingHouse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publishingHouse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($publishingHouse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('publishing_house_index');
    }
}
