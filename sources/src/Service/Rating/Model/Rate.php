<?php

namespace App\Service\Rating\Model;


use Symfony\Component\Validator\Constraints as Assert;

class Rate
{
  public function __construct(
    public int|string $bookId,
    #[Assert\GreaterThan(0)]
    #[Assert\LessThan(6)]
    public int|float $rating
  ) {}
}
