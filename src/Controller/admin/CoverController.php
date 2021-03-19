<?php

namespace App\Controller\Admin;

use App\Entity\Cover;
use App\Form\CoverType;
use App\Repository\CoverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/cover", name=""admin_cover_)
 * @package App\Controller\Admin
 */
class CoverController extends AbstractController
{
    /**
     * @Route("/", name="cover_index", methods={"GET"})
     */
    public function index(CoverRepository $coverRepository): Response
    {
        return $this->render('cover/index.html.twig', [
            'covers' => $coverRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cover_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cover = new Cover();
        $form = $this->createForm(CoverType::class, $cover);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cover);
            $entityManager->flush();

            return $this->redirectToRoute('cover_index');
        }

        return $this->render('cover/new.html.twig', [
            'cover' => $cover,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cover_show", methods={"GET"})
     */
    public function show(Cover $cover): Response
    {
        return $this->render('cover/show.html.twig', [
            'cover' => $cover,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cover_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cover $cover): Response
    {
        $form = $this->createForm(CoverType::class, $cover);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cover_index');
        }

        return $this->render('cover/edit.html.twig', [
            'cover' => $cover,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cover_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cover $cover): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cover->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cover);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cover_index');
    }
}
