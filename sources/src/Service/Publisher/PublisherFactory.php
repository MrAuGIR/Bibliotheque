<?php

namespace App\Service\Publisher;

use App\Entity\PublishingHouse;

class PublisherFactory
{
    public function create(string $name): PublishingHouse
    {
        return (new PublishingHouse())
            ->setName($name)
            ->setShortDescription('');
    }
}