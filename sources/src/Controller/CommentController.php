<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\Comment\EditCommentDto;
use App\Service\Comment\CommentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/book/{id}', name: 'book', methods: ['GET', 'POST'])]
    public function list(Book $book, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_book', ['id' => $book->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBook($book);
            $comment->setIsActive(true);
            $comment->setCreatedAt((new \DateTimeImmutable('now')));
            $comment->setAuthor($this->getUser());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('book_show', ['id' => $book->getApiId()]);
        }

        return $this->render('comment/index.html.twig', [
            'comments' => $book->getComments() ?? [],
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] EditCommentDto $dto, CommentManager $manager): JsonResponse
    {
        $manager->update($dto);

        return $this->json([]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, CommentManager $manager): JsonResponse
    {
        $manager->delete($id);
        return $this->json([]);
    }
}
