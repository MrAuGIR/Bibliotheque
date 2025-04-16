<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BiblioRepository;
use App\Service\Biblio\Event\SearchBiblioEvent;
use App\Service\Biblio\Input\InputSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BiblioController extends AbstractController
{
    #[Route("/biblio/popularity", name: 'popularity_biblio', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function popularity(BiblioRepository $repository): Response
    {
        $popularityBiblio = $repository->getMostPopularBiblio();

        return $this->render('home/_bibliopopularity.html.twig', [
            'popularityBiblio' => $popularityBiblio,
        ]);
    }

    #[Route("/biblio/search", name: 'biblio_search', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function search(#[MapQueryString] InputSearch $search, EventDispatcherInterface $dispatcher): Response
    {
        $event = $dispatcher->dispatch(new SearchBiblioEvent($search, null), SearchBiblioEvent::NAME);

        $output = $event->getOutputSearch();

        return $this->render('biblio/search.html.twig', [
            'biblios' => []
        ]);
    }

    #[Route('/biblio/{id}', name: 'index_biblio', methods: [Request::METHOD_GET])]
    public function index(Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        $books = $biblio->getBooks();

        if ($biblio->updateCountViews($this->getUser())) {
            $entityManager->persist($biblio);
            $entityManager->flush();
        }

        return $this->render('biblio/index.html.twig', compact('biblio', 'books'));
    }

    #[Route("/biblio/create", name: 'create_book_biblio', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function add(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @todo quand les utilisateurs pourront créer plusieurs biblio */
        }
        return $this->render("biblio/add.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
