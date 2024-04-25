<?php

namespace App\Service\Api\Model;

use App\Service\Api\Model\Access\Epub;
use App\Service\Api\Model\Access\Pdf;

class AccessInfo
{
    protected string $country;

    protected string $viewability;

    protected bool $embeddable = false;

    protected bool $publicDomain = false;

    protected Epub $epub;

    protected Pdf $pdf;

    protected string $webReaderLink;

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getViewability(): string
    {
        return $this->viewability;
    }

    public function setViewability(string $viewability): void
    {
        $this->viewability = $viewability;
    }

    public function isEmbeddable(): bool
    {
        return $this->embeddable;
    }

    public function setEmbeddable(bool $embeddable): void
    {
        $this->embeddable = $embeddable;
    }

    public function isPublicDomain(): bool
    {
        return $this->publicDomain;
    }

    public function setPublicDomain(bool $publicDomain): void
    {
        $this->publicDomain = $publicDomain;
    }

    public function getEpub(): Epub
    {
        return $this->epub;
    }

    public function setEpub(Epub $epub): void
    {
        $this->epub = $epub;
    }

    public function getPdf(): Pdf
    {
        return $this->pdf;
    }

    public function setPdf(Pdf $pdf): void
    {
        $this->pdf = $pdf;
    }

    public function getWebReaderLink(): string
    {
        return $this->webReaderLink;
    }

    public function setWebReaderLink(string $webReaderLink): void
    {
        $this->webReaderLink = $webReaderLink;
    }
}