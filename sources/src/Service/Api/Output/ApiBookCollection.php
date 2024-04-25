<?php

namespace App\Service\Api\Output;

use App\Service\Api\Model\ApiBook;

class ApiBookCollection
{
    /**
     * @var ApiBook[]
     */
    public array $items;

    /**
     * @return ApiBook[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}