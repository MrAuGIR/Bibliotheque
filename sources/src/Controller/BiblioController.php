<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BiblioController extends AbstractController
{
    #[Route('/biblio', name: 'index_biblio', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        $user = $this->getUser();

        $biblio = $user->getBiblio();
        $books = $biblio->getBooks();

        return $this->render('biblio/index.html.twig', compact('biblio', 'books'));
    }

    #[Route("/biblio/create", name: 'create_book_biblio', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function add(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }
        return $this->render("biblio/add.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
