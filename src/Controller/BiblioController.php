<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class BiblioController extends AbstractController
{
    /**
     * @Route("/biblio", name="index_biblio")
     */
    public function index(): Response
    {
        //utilisateur connecté
        /** @var User $user */
        $user = $this->getUser();

        $biblio = $user->getBiblio();
        $books = $biblio->getBooks();


        return $this->render('biblio/index.html.twig', compact('books', 'biblio'));
    }

    /**
     * @Route("/biblio/add", name="add_book_biblio")
     */
    public function add():Response
    {
        return $this->json(['message'=>'livre ajouté'],200);
    }


    
}
