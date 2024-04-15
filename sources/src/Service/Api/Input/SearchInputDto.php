<?php

namespace App\Service\Api\Input;

use Symfony\Component\HttpFoundation\Request;

readonly class SearchInputDto
{
    public function __construct(
        public string  $q,
        public ?string $l,
        public bool    $free = false
    )
    {
    }

    public function buildQuery(Request $request): string
    {
        return "";
    }
}