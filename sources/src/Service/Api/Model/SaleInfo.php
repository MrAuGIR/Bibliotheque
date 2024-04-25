<?php

namespace App\Service\Api\Model;

class SaleInfo
{
    protected string $country;

    protected string $buyLink;

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getBuyLink(): string
    {
        return $this->buyLink;
    }

    public function setBuyLink(string $buyLink): void
    {
        $this->buyLink = $buyLink;
    }


}