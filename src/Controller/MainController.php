<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\WriterRepository;
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
    public function index(Request $request, BookRepository $bookRepository): Response
    {
        $lastBooks = $bookRepository->findLastBookPosted();

        return $this->render('main/index.html.twig', [
            'lastBooks' => $lastBooks,
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


    /**
     * @Route("/search", name="search_home", methods={"GET","POST"})
     */
    public function search(Request $request, HttpClientInterface $client, WriterRepository $writerRepository, BookRepository $bookRepository):Response
    {
        $query = $request->query->get('q');
        $limit = $request->query->get('l',10);
        // on recupère l'info case a coche free
        $isFree = $request->query->get('free');
        $paramFree = !empty($isFree) ? '&filter=free-ebook' : '';

        //clé api
        $apiKey = $this->getParameter('API_BOOK');

        //on recupère les écrivains
        $writers = $writerRepository->findBySearchQuery($query);

        
        //construction des paramètres
        $params = '?q=' . $query . $paramFree . '&maxResults=40&key=' . $apiKey;

        //envoie de la requète vers l'api google book
        $reponse = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes' . $params);
        $content = $reponse->toArray();

        return $this->json([
            'result' => $this->renderView('main/_result.html.twig',[
                'books' => array_slice($content['items'],0,8),
                'writers' => $writers,
            ]),
        ]);


    }
}
