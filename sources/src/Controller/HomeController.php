<?php

namespace App\Controller;

use App\Service\Api\GoogleBook;
use App\Service\Api\Input\SearchInputDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private GoogleBook $googleBook
    )
    {
    }

    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('home/index.html.twig');
    }

    #[Route("/actu", name: "app_home_actu", methods: ['GET','POST'])]
    public function getActu(Request $request) : JsonResponse
    {
        return $this->json([]);
    }

    #[Route("/search", name: "app_home_seach", methods: ['GET','POST'])]
    public function search(
        #[MapRequestPayload] SearchInputDto $searchInputDto,
    ) : JsonResponse
    {
        $data = $this->googleBook->call($searchInputDto);

        return $this->json($data);
    }
}
