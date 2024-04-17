<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\Api\GoogleBook;
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
    #[Route('/book/{id}/show', name: 'show_book', methods: [Request::METHOD_GET])]
    public function show(int $id,Request $request, EntityManagerInterface $em): Response
    {
        $data = $this->googleBook->get($id);

        $book = $em->getRepository(Book::class)->findOneBy(['apiId' => $id]);

        return $this->render('book/show.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
}
