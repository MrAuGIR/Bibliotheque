<?php

namespace App\Service\Api\Input;

use Symfony\Component\Validator\Constraints as Assert;

readonly class SearchInputDto
{
    private const QUERY_PATTERN = "?q=%s&maxResults=%s&key=%s";

    public function __construct(
        #[Assert\Length(min: 3, max: 33)]
        public ?string  $keywords,

        public ?string $tag = null,

        #[Assert\LessThan(40)]
        public int $l = 10,
        public bool $free = false
    ) {}

    public function buildQuery(string $apiKey): string
    {
        $searchTerm = $this->keywords ?? $this->getFormattedTag();
        return sprintf(
            self::QUERY_PATTERN,
            $searchTerm,
            $this->l,
            $apiKey
        );
    }

    private function getFormattedTag(): string
    {
        if ($this->tag) {
            return 'subject:' . strtolower($this->tag);
        }

        return '';
    }

    public function hasValidSearch(): bool
    {
        return !empty($this->keywords) || !empty($this->tag);
    }
}

