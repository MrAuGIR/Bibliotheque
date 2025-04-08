<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Entity\Book;
use App\Entity\User;
use App\Service\Api\GoogleBook;
use App\Service\Api\Input\FecthInputDto;
use App\Service\Api\Input\SearchInputDto;
use App\Service\Book\BookManager;
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
    ) {}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/{id}/show', name: 'show', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function show(string $id, Request $request): Response
    {
        $apiBook = $this->googleBook->get($id);

        $book = $this->entityManager->getRepository(Book::class)->findOneBy(['apiId' => $id]);

        $dto = new SearchInputDto('+inauthor:' . $apiBook->getVolumeInfo()->getAuthors()[0], 9);
        $relatedBooks = $this->googleBook->list($dto);


        return $this->render('book/show.html.twig', [
            'book' => $apiBook,
            'booksRelated' => $relatedBooks,
            'entityId' => $book?->getId() ?? null

        ]);
    }

    #[Route('/search', name: "search", methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function search(): Response
    {

        return $this->render('book/search.html.twig', []);
    }

    #[Route("/add", name: "add_to_biblio", methods: [Request::METHOD_POST])]
    public function addBook(
        #[MapRequestPayload] FecthInputDto $fecthInputDto,
        BookManager $manager
    ): JsonResponse {
        $apiBook = $this->googleBook->get($fecthInputDto->getId());

        $book = $manager->load($apiBook);

        $biblio = $this->loadUserBiblio();

        $biblio->addBook($book);

        $this->entityManager->persist($biblio);
        $this->entityManager->flush();

        return $this->json(['id' => $book->getId()]);
    }

    #[Route("/{id}", name: "remove_to_biblio", methods: [Request::METHOD_DELETE])]
    public function removeBook(string $id, Request $request): JsonResponse
    {
        if (!empty($book = $this->entityManager->getRepository(Book::class)->findOneBy(['id' => $id]))) {
            $biblio = $this->loadUserBiblio();
            $biblio->removeBook($book);
            $this->entityManager->persist($biblio);
            $this->entityManager->flush();

            $this->addFlash('success', 'Book retirer de votre biblio');
            return $this->json(['status' => 'success', 'api_id' => $book->getApiId()]);
        }
        return $this->json(['status' => 'failed : not book found with this id'], 500);
    }

    private function loadUserBiblio(): Biblio
    {
        /** @var User $user */
        $user = $this->getUser();
        return $user->getBiblio();
    }
}
