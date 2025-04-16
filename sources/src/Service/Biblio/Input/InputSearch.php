<?php

namespace App\Service\Biblio\Input;

class InputSearch
{
    public function __construct(
        public ?string $code
    ) {}
}
