<?php

namespace App\Service\Api\Output;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly class SearchJsonOutput
{
    public function __construct(
        private string $render
    )
    {
    }

    public function getOutput(): JsonResponse
    {
        return new JsonResponse([
            'result' => $this->render,
        ]);
    }
}