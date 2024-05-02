<?php

namespace App\Service\Publisher;

use App\Entity\PublishingHouse;
use App\Repository\PublishingHouseRepository;

readonly class Loader
{
    public function __construct(
        private PublishingHouseRepository $repository,
        private PublisherFactory          $factory
    ){}

    public function load(string $name): PublishingHouse
    {
        if (empty($publisher = $this->getPublishers($name))) {
            $publisher = $this->factory->create($name);
        }
        return $publisher;
    }

    public function getPublishers(string $name):?PublishingHouse
    {
        return $this->repository->findOneBy(['name' => $name]);
    }
}