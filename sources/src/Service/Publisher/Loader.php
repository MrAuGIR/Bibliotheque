<?php

namespace App\Service\Publisher;

use App\Entity\PublishingHouse;
use App\Repository\PublishingHouseRepository;

class Loader
{
    public function __construct(
        private PublishingHouseRepository $repository,
    ){}

    public function getPublishers(string $name):?PublishingHouse
    {
        return $this->repository->findOneBy(['name' => $name]);
    }
}