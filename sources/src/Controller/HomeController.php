<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
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

    #[Route("/seach", name: "app_home_seach", methods: ['GET'])]
    public function search(Request $request) : JsonResponse
    {
        return $this->json([]);
    }
}
