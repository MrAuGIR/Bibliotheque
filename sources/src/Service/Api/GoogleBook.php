<?php

namespace App\Service\Api;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GoogleBook
{
    public function __construct(
        #[Autowire(param: 'API_BOOK')]  private string $apiKey
    ){}
}