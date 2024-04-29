<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BiblioController extends AbstractController
{
    #[Route('/biblio', name: 'index_biblio')]
    public function index(): Response
    {
        $user = $this->getUser();

        $biblio = $user->getBiblio();
        $books = $biblio->getBooks();

        return $this->render('biblio/index.html.twig', compact('biblio', 'books'));
    }

    #[Route("/biblio/add", name: 'add_book_biblio', methods: ['POST'])]
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
