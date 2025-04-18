<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Tag;
use App\Service\Api\Model\ApiBook;
use App\Service\Api\Model\VolumeInfo;
use App\Service\Tag\TagManager;
use DateTimeImmutable;

readonly class BookFactory
{
    public function __construct(
        private \App\Service\Publisher\Loader $loader,
        private TagManager $tagManager,
    ) {}

    public function createFromApiBook(ApiBook $apiBook): Book
    {
        $book = (new Book())
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

        $this->associatedTagToBook($apiBook->getBookCategories(), $book);
        return $book;
    }

    private function createDate(string $date): \DateTimeImmutable
    {
        if ($date === '') {
            return new DateTimeImmutable('now');
        }

        $formats = [
            \DateTimeInterface::ATOM,
            'Y-m-d',
            'Y-m-d H:i:s'
        ];

        foreach ($formats as $format) {
            $dateTime = DateTimeImmutable::createFromFormat($format, $date);
            if (is_bool($dateTime)) {
                continue;
            }
            return $dateTime;
        }

        return new DateTimeImmutable('now');
    }

    public function associatedTagToBook(array $apiCategories, Book $book): void
    {
        if (empty($apiCategories)) {
            $book->addTag($this->tagManager->load('unknown'));
        }
        if (!empty($categories = $apiCategories[0] ?? null)) {
            if (is_array($categories)) {
                $book->addTag($this->tagManager->load('unknown'));
                return;
            }
            $list = explode('/', $categories);
            foreach ($this->getOrCreateTags($list) as $tag) {
                $book->addTag($tag);
            }
        }
    }

    /**
     * @return Tag[]
     */
    private function getOrCreateTags(array $categories): \Generator
    {
        foreach ($categories as $category) {
            yield $this->tagManager->load($category);
        }
    }
}
