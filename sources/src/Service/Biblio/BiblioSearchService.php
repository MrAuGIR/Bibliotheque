<?php

namespace App\Service\Biblio;

use App\Service\Biblio\Event\SearchBiblioEvent;
use App\Service\Biblio\Input\InputSearch;
use App\Service\Biblio\Output\OutputSearch;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BiblioSearchService
{
    public function __construct(
        private EventDispatcherInterface $dispatcher
    ) {}

    public function search(InputSearch $InputSearch, OutputSearch $outputSearch): OutputSearch
    {
        $event = new SearchBiblioEvent($InputSearch, $outputSearch);

        $this->dispatcher->dispatch($event, SearchBiblioEvent::NAME);

        return $event->getOutputSearch();
    }
}
