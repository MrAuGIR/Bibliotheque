<?php

namespace App\Service\Api\Input;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SearchInputDto
{
    private const QUERY_PATTERN = "?q=%s&maxResults=%s&key=%s";

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 33)]
        public string  $q,

        #[Assert\LessThan(40)]
        public int $l = 10,
        public bool $free = false
    )
    {
    }

    public function buildQuery(string $apiKey): string
    {
        return sprintf(
            self::QUERY_PATTERN,
            $this->q,
            $this->l,
            $apiKey
        );
    }
}