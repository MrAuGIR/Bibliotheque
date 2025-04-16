<?php

namespace App\Service\Biblio\Output;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TwigOutputSearch implements OutputSearch
{
    private array $biblios = [];

    public function __construct(
        private Environment $twig,
        private string $template = 'biblio/search.html.twig'
    ) {}

    public function setBiblios(array $biblios): OutputSearch
    {
        $this->biblios = $biblios;
        return $this;
    }

    public function getResponse()
    {
        return new Response(
            $this->twig->render($this->template, [
                'biblios' => $this->biblios
            ])
        );
    }
}
