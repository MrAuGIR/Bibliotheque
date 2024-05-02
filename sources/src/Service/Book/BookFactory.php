<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Service\Api\Model\ApiBook;

class BookFactory
{
    public function createFromApiBook(ApiBook $apiBook): Book
    {
        $book = (new Book())
            ->setApiId($apiBook->getId())
            ->setTitle($apiBook->getVolumeInfo()->getTitle())
            ->setDescription($apiBook->getVolumeInfo()->getDescription())
            ->setISBN($apiBook->getVolumeInfo()->getIndustryIdentifiers()['identifier'][0] ?? '')
            ->setPublishingHouse();
    }
}