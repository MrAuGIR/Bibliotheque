<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Service\Api\Model\ApiBook;

readonly class BookFactory
{
    public function __construct(
        private \App\Service\Publisher\Loader $loader
    )
    {

    }
    public function createFromApiBook(ApiBook $apiBook): Book
    {
        return (new Book())
            ->setApiId($apiBook->getId())
            ->setTitle($apiBook->getVolumeInfo()->getTitle())
            ->setDescription($apiBook->getVolumeInfo()->getDescription())
            ->setISBN($apiBook->getVolumeInfo()->getIndustryIdentifiers()[0]['identifier'] ?? '')
            ->setPublishingHouse(
                $this->loader->load(
                    $apiBook->getVolumeInfo()->getPublisher()
                )
            )
            ->setThumbnails([
                'small' => $apiBook->getVolumeInfo()->getImageLinks()['smallThumbnail'] ?? '',
                'medium' => $apiBook->getVolumeInfo()->getImageLinks()['thumbnail'] ?? '',
            ])
            ->setPublishedAt($this->createDate($apiBook->getVolumeInfo()->getPublishedDate()));
    }

    private function createDate(string $date): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $date);
    }
}