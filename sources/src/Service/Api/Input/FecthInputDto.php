<?php

namespace App\Service\Api\Input;

class FecthInputDto
{
    public function __construct(
        public string $id
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }


}