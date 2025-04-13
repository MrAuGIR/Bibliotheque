<?php

namespace App\DataFixtures;

use App\Entity\Biblio;
use App\Service\Api\GoogleBook;
use App\Service\Api\Input\SearchInputDto;
use App\Service\Api\Model\ApiBook;
use App\Service\Book\BookManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public const KEYWORDS = [
        'dumas',
        'zola',
        'victor hugo',
        'naruto',
        'one piece',
        'harry potter',
        'céline',
        'molière'
    ];

    private $factory;

    /**
     *@var ApiBook[] $books 
     */
    private array $apiBooks;

    /**
     * @var string[] $loadedApiId
     */
    private array $loadedApiId = [];

    private array $tempBook;

    private array $userSearchs;

    public function __construct(
        private GoogleBook $googleBook,
        private BookManager $BookManager
    ) {
        $this->factory = Factory::create();
        $this->buildUserSearch();
        $this->buildBookList();
    }

    public function load(ObjectManager $manager)
    {
        $biblios = $manager->getRepository(Biblio::class)->findAll();

        foreach ($biblios as $biblio) {
            $numberBookToAdd = $this->factory->numberBetween(2, 6);

            for ($i = 0; $i < $numberBookToAdd; $i++) {

                $randomKeyword = self::KEYWORDS[array_rand(self::KEYWORDS)];

                if (!isset($this->apiBooks[$randomKeyword]) || empty($this->apiBooks[$randomKeyword])) {
                    continue;
                }

                $randomIndex = array_rand($this->apiBooks[$randomKeyword]);
                /** @var ApiBook $apiBook */
                $apiBook = $this->apiBooks[$randomKeyword][$randomIndex];

                if ($apiBook->getId() && in_array($apiBook->getId(), $this->loadedApiId)) {
                    continue;
                }

                $this->loadedApiId[] = $apiBook->getId();

                $entityBook = $this->BookManager->load($apiBook);
                $biblio->addBook($entityBook);

                $manager->persist($biblio);
            }
        }

        $manager->flush();
    }

    private function buildUserSearch(): void
    {
        foreach (self::KEYWORDS as $kerword) {
            $this->userSearchs[] = $this->buildSearchInputDto($kerword);
        }
    }

    private function buildSearchInputDto(string $kerword): SearchInputDto
    {
        return new SearchInputDto(
            $kerword,
            null,
            5,
            false
        );
    }

    private function buildBookList(): void
    {
        foreach ($this->userSearchs as $search) {
            $collection = $this->googleBook->list($search);
            $this->apiBooks[$search->keywords] = $collection->items;
        }
    }

    public function getDependencies(): array
    {
        return [
            BiblioFixtures::class
        ];
    }
}
