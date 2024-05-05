<?php

namespace App\Twig;

use App\Entity\Biblio;
use App\Entity\Book;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BookExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('isInUserBiblio',[$this, 'isInUserBiblio']),
            new TwigFilter('isApiBook',[$this, 'isApiBook']),
        ];
    }

    public function isInUserBiblio(Biblio $biblio, string $id): bool
    {
        foreach($biblio->getBooks() as $book) {
            if ($book->getApiId() === $id) {
                return true;
            }
        }
        return false;
    }

    public function isApiBook(Book $book): bool
    {
        return $book->getApiId() !== null;
    }
}