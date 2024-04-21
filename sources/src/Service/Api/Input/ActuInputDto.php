<?php

namespace App\Service\Api\Input;

class ActuInputDto {
    const PATTERN_QUERY = "?q=%s&from=%s&sortBy=%s&apiKey=%s";
    public function __construct(
        public string $query,
        public ?string $from,
        public string $sortBy = 'literature',
    )
    {
    }

    /**
     * @param string $apiKey
     * @return string
     */
    public function buildQuery(string $apiKey): string
    {
        return sprintf(
            self::PATTERN_QUERY,
            $this->query,
            $this->from ?? $this->getDateFormat(),
            $this->sortBy,
            $apiKey
        );
    }

    private function getDateFormat(): string
    {
        return (new \DateTime())->modify('-1 day')->format('Y-m-d');
    }
}