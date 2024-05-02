<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\Api\Model\ApiBook;

readonly class Loader
{
    public function __construct(
        private BookRepository $bookRepository,
        private BookFactory    $factory
    )
    {
    }

    public function load(ApiBook $apiBook): Book
    {
        if (empty($book = $this->getBook($apiBook->getId()))) {
            $book = $this->factory->createFromApiBook($apiBook);
        }
        return $book;
    }

    public function getBook(string $apiId): ?Book
    {
        return $this->bookRepository->findOneBy(['apiId' => $apiId]);
    }
}