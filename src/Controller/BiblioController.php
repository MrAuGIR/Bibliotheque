<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BiblioController extends AbstractController
{
    /**
     * @Route("/biblio", name="index_biblio")
     */
    public function index(): Response
    {
        return $this->render('biblio/index.html.twig', [
            'controller_name' => 'BiblioController',
        ]);
    }


    /**
     * ajout dans la biblio de l'utilisateur d'un livre depuis l'api google book
     * @Route("/biblio/add/apibook/{id}", name="add_apibook_biblio", methods={"GET"})
     */
    public function add():Response
    {
        return $this->json(['status'=> true],200);
    }
}
