<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Entity\Book;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Service\Api\GoogleBook;
use App\Service\Api\Input\FecthInputDto;
use App\Service\Api\Input\SearchInputDto;
use App\Service\Book\Loader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route("/book", name: "book_")]
class BookController extends AbstractController
{
    public function __construct(
        private readonly GoogleBook $googleBook,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/{id}/show', name: 'show', methods: [Request::METHOD_GET,Request::METHOD_POST])]
    public function show(string $id,Request $request): Response
    {
        $apiBook = $this->googleBook->get($id);

        $book = $this->entityManager->getRepository(Book::class)->findOneBy(['apiId' => $id]);

        $dto = new SearchInputDto('+inauthor:'.$apiBook->getVolumeInfo()->getAuthors()[0],9);
        $relatedBooks = $this->googleBook->list($dto);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBook($book);
            $comment->setIsActive(true);
            $comment->setCreatedAt((new \DateTimeImmutable('now')));
            $comment->setAuthor($this->getUser());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('book_show',['id' => $book->getApiId()]);
        }

        return $this->render('book/show.html.twig', [
            'book' => $apiBook,
            'booksRelated' => $relatedBooks,
            'comments' => $book?->getComments() ?? [],
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/search', name: "search", methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function search(): Response
    {

        return $this->render('book/search.html.twig',[

        ]);
    }

    #[Route("/add", name: "add_to_biblio", methods: [Request::METHOD_POST])]
    public function addBook(
        #[MapRequestPayload] FecthInputDto $fecthInputDto,
        Loader $loader
    ): JsonResponse
    {
        $apiBook = $this->googleBook->get($fecthInputDto->getId());

        $book = $loader->load($apiBook);

        $biblio = $this->loadUserBiblio();

        $biblio->addBook($book);

        $this->entityManager->persist($biblio);
        $this->entityManager->flush();

        return $this->json($book);
    }

    #[Route("/{id}", name: "remove_to_biblio", methods: [Request::METHOD_DELETE])]
    public function removeBook(string $id,Request $request): JsonResponse
    {
        if (!empty($book = $this->entityManager->getRepository(Book::class)->findOneBy(['id' => $id]))) {
            $biblio = $this->loadUserBiblio();
            $biblio->removeBook($book);
            $this->entityManager->persist($biblio);
            $this->entityManager->flush();
        }
        return $this->json([]);
    }

    private function loadUserBiblio(): Biblio
    {
        /** @var User $user */
        $user = $this->getUser();
        return $user->getBiblio();
    }
}
