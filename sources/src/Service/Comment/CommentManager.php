<?php

namespace App\Service\Comment;

use App\Entity\Comment;
use App\Exception\CommentNotFoundException;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private CommentRepository $commentRepository
    ) {}

    public function load(string|int $id): ?Comment
    {
        if (empty($comment = $this->getComment($id))) {
            return null;
        }
        return $comment;
    }

    public function update(EditCommentDto $dto): void
    {
        if (empty($comment = $this->getComment($dto->id))) {
            throw new CommentNotFoundException("not comment found with this id " . $dto->id);
        }
        $comment->setContent($dto->content);
        $this->em->persist($comment);
        $this->em->flush();
    }

    public function delete(string|int $id): void
    {
        if (empty($comment = $this->getComment($id))) {
            throw new CommentNotFoundException("no comment found with this id " . $id);
        }
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function getComment(string|int $id): Comment
    {
        return $this->commentRepository->find($id);
    }
}
