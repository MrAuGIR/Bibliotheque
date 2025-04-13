<?php

namespace App\Service\Api\Model;

class VolumeInfo
{
    protected string $title;

    protected string $subtitle = '';

    protected array $authors;

    protected string $publisher = '';

    protected string $publishedDate;

    protected string $description = '';

    protected array $industryIdentifiers = [];

    protected int $pageCount = 0;

    protected int $printedPageCount = 0;

    protected array $categories = [];

    protected array $imageLinks = [];

    protected string $language = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): void
    {
        $this->publisher = $publisher;
    }

    public function getPublishedDate(): string
    {
        return $this->publishedDate;
    }

    public function setPublishedDate(string $publishedDate): void
    {
        $this->publishedDate = $publishedDate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getIndustryIdentifiers(): array
    {
        return $this->industryIdentifiers;
    }

    public function setIndustryIdentifiers(array $industryIdentifiers): void
    {
        $this->industryIdentifiers = $industryIdentifiers;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): void
    {
        $this->pageCount = $pageCount;
    }

    public function getPrintedPageCount(): int
    {
        return $this->printedPageCount;
    }

    public function setPrintedPageCount(int $printedPageCount): void
    {
        $this->printedPageCount = $printedPageCount;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function getImageLinks(): array
    {
        return $this->imageLinks;
    }

    public function setImageLinks(array $imageLinks): void
    {
        $this->imageLinks = $imageLinks;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }
}
