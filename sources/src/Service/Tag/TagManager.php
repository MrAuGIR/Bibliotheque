<?php

namespace App\Service\Tag;

use App\Entity\Tag;
use App\Repository\TagRepository;

class TagManager
{
    public function __construct(
        private TagRepository $repository
    ) {}

    public function load(string $code): Tag
    {
        if (empty($tag = $this->getTag($code))) {
            $tag = new Tag();
            $tag->setCode($code);
            $tag->setColor('#ffffff');
        }
        return $tag;
    }

    public function getTag(string $code): ?Tag
    {
        return $this->repository->findOneBy(['code' => $code]);
    }
}
