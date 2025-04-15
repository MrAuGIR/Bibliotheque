<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const TAGS = [
        "Comics & Graphic Novels",
        "Theater",
        "Fiction",
        "Literary Collections",
        "Literature and medicine",
        "Biography & Autobiography",
        "Philosophy",
        "unknown",
        "Contemplation in literature",
        "Young Adult Fiction",
        "French drama",
        "Authors, French",
        "Juvenile Fiction",
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::TAGS as $key => $tag) {
            $tagO = new Tag();
            $tagO->setCode($tag);
            $tagO->setColor('#ff0000');

            $manager->persist($tagO);
            $this->addReference('tag-' . $key, $tagO);
        }

        $manager->flush();
    }
}
