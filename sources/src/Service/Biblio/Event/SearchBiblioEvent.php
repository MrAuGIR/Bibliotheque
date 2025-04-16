<?php




namespace App\Service\Biblio\Event;

use App\Service\Biblio\Input\InputSearch;
use App\Service\Biblio\Output\OutputSearch;
use Symfony\Contracts\EventDispatcher\Event;

class SearchBiblioEvent extends Event
{
    public const NAME = 'app.biblio.search';

    public function __construct(
        private InputSearch $search,
        private OutputSearch $outputSearch
    ) {}

    public function getInputSearch(): InputSearch
    {
        return $this->search;
    }

    public function getOutputSearch(): OutputSearch
    {
        return $this->outputSearch;
    }

    public function setOutputSearch(OutputSearch $outputSearch): self
    {
        $this->outputSearch = $outputSearch;
        return $this;
    }
}
