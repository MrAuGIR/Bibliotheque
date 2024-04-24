<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\Api\GoogleBook;
use App\Service\Api\Input\SearchInputDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route("/book", name: "book_")]
class BookController extends AbstractController
{
    public function __construct(
        private readonly GoogleBook $googleBook
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/{id}/show', name: 'show', methods: [Request::METHOD_GET])]
    public function show(string $id,Request $request, EntityManagerInterface $em): Response
    {
        $data = $this->googleBook->get($id);

        $book = $em->getRepository(Book::class)->findOneBy(['apiId' => $id]);

        $author = "jojo"; // test
        /** @todo related book author */
        $dto = new SearchInputDto('+inauthor:'.$author,9);
        $relatedBooks = $this->googleBook->list($dto);

        return $this->render('book/show.html.twig', [
            'book' => $data,
            'booksRelated' => $relatedBooks
        ]);
    }

    #[Route('/search', name: "search", methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function search(): Response
    {

        return $this->render('book/search.html.twig',[

        ]);
    }
}
