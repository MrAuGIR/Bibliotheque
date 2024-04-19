<?php

namespace App\Controller;

use App\Service\Api\GoogleBook;
use App\Service\Api\Input\ActuInputDto;
use App\Service\Api\Input\SearchInputDto;
use App\Service\Api\Output\SearchJsonOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly GoogleBook $googleBook
    )
    {
    }

    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('home/index.html.twig',[
            'articles' => null
        ]);
    }

    #[Route("/actu", name: "app_home_actu", methods: ['POST'])]
    public function getActu(
        #[MapRequestPayload] ActuInputDto $actuInputDto
    ) : JsonResponse
    {


        return $this->json([]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route("/search", name: "app_home_seach", methods: ['GET','POST'])]
    public function search(
        #[MapRequestPayload] SearchInputDto $searchInputDto,
    ) : JsonResponse
    {
        $writers = [];
        $data = $this->googleBook->list($searchInputDto);

        return (new SearchJsonOutput($this->renderView('home/_results.html.twig', [
            'books' => array_slice($data['items'],0,8),
            'writers' => $writers
        ])))->getOutput();
    }
}
