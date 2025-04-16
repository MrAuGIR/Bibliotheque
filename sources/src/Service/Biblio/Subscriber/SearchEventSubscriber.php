<?php



namespace App\Service\Biblio\Subscriber;

use App\Repository\BiblioRepository;
use App\Service\Biblio\Event\SearchBiblioEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BiblioRepository $repository
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            SearchBiblioEvent::NAME => 'onSearchInput'
        ];
    }

    public function onSearchInput(SearchBiblioEvent $event): void
    {
        $input = $event->getInputSearch();

        if (!empty($biblios = $this->repository->getBiblioByTag($input->code))) {
            $output = $event->getOutputSearch();
            $output->setBiblios($biblios);
        }
    }
}
