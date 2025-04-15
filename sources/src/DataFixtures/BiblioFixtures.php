<?php

namespace App\DataFixtures;

use App\Entity\Biblio;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BiblioFixtures extends Fixture implements DependentFixtureInterface
{
    private $factory;

    public function __construct()
    {
        $this->factory = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $biblio = new Biblio();
            $biblio->setTitle($this->factory->words(3, true) . ' Library');
            $biblio->setCreatedAt(new DateTimeImmutable($this->factory->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')));
            $biblio->setViews($this->factory->numberBetween(1, 1000));
            $biblio->setUser($user);
            $this->addRandomTags($biblio, $this->factory->numberBetween(0, 4));
            $manager->persist($biblio);
        }

        $manager->flush();
    }

    public function addRandomTags(Biblio $biblio, int $numberTag): void
    {
        for ($i = 0; $i < $numberTag; $i++) {
            $randomIndex = $this->factory->numberBetween(0, 12);
            if (empty($tag = $this->getReference('tag-' . $randomIndex))) {
                continue;
            }
            $biblio->addTag($tag);
        }
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class,
            TagFixtures::class
        ];
    }
}
