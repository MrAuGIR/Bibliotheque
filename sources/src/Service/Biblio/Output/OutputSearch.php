<?php




namespace App\Service\Biblio\Output;

use App\Entity\Biblio;
use Symfony\Component\HttpFoundation\Response;

class OutputSearch
{
    /** @var Biblio[] $biblios */
    private array $biblios;

    public function __construct()
    {
        $this->biblios = [];
    }

    public function setBiblios(array $biblios): self
    {
        $this->biblios = $biblios;
        return $this;
    }

    public function getResponse(string $string): Response
    {
        return new Response($string);
    }
}
