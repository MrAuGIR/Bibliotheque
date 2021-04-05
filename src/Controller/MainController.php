<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'articles'=> $articles =""
        ]);
    }



    /**
     * @Route("/actu", name="actu_home", methods={"GET","POST"})
     */
    public function actu(Request $request, HttpClientInterface $client):Response
    {
        $q = $request->get('q');

        $from = (new \DateTime())->format('Y-m-d');

        $reponse = $client->request('GET', 'https://newsapi.org/v2/everything?q=literary&from='.$from.'&sortBy=literature&apiKey='.$this->getParameter('API_ACTU'));
        
        $content = $reponse->toArray();
        
        $articles = $content['articles'];
        
        return $this->json(['content'=>$this->renderView('main/_actu.html.twig',[
            'articles' => $articles
        ])],200);
    }
}
