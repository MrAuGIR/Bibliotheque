<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Knp\Component\Pager\PaginatorInterface;

class BookController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/book", name="index_book")
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/book/search", name="search_book", methods={"GET","POST"})
     */
    public function search(Request $request, PaginatorInterface $paginator): Response
    {
        $limit = 10;

        // recupération du critère de recherche
        $keywords = $request->query->get('keywords');

        //recuperation de la selection du champs de recherche
        $filterField = $request->query->get('filterField');

        if($filterField != 'intitle'){
            $keywords = '+'.$keywords.':'.$filterField;
        }

        //on recupère la page en cours pour la pagination
        $page = (int)$request->query->get('page',1);
        
        // on recupère l'info case a coche free
        $isFree = $request->query->get('free');
        $paramFree = !empty($isFree)? '&filter=free-ebook':'';
        
        //index de départ
        $startIndex = ($page * $limit) - $limit;

        //clé api
        $apiKey = 'AIzaSyCYCOR-Vs-22O1A-kx1hr1k2eH4g0k--VI';

        //construction des paramètres
        //$params = '?q='.$keywords.$paramFree.'&maxResults='.$limit . '&startIndex=' . $startIndex . '&key='.$apiKey;
        $params = '?q=' . $keywords . $paramFree . '&maxResults=40&key=' . $apiKey;
       
        //envoie de la requète vers l'api google book
        $reponse = $this->client->request('GET', 'https://www.googleapis.com/books/v1/volumes' . $params);
        $content = $reponse->toArray();

        $books = $paginator->paginate($content['items'], $page, $limit);

       // $total = ($content['totalItems'] > 100) ? 100  : $content['totalItems'];

        if($reponse->getStatusCode() != 200){
        
            $this->addFlash('warning','serveur en maintenance');
            return $this->render("book/search.html.twig", [
                'page' => $page,
                'total' => 0,
                'limit' => $limit
            ]);
        }

        //si c'est un requète ajax
        if ($request->query->get('ajax')) {
           
            return new JsonResponse([
                'content' => $this->renderView('book/_content.html.twig', [
                    'books' => $books,
                    'page' => $page,
                    'limit' => $limit,
                ])
            ]);
            
        }
        
        
        return $this->render("book/search.html.twig", [
            'books' => $books,
            'page' => $page,
            'total' => 0,
            'limit' => $limit
        ]);
    }


    /**
     * @Route("/book/show/{id}", name="show_book", methods={"GET","POST"})
     */
    public function show($id):Response
    {
        //clé api
        $apiKey = 'AIzaSyCYCOR-Vs-22O1A-kx1hr1k2eH4g0k--VI';
        $reponse = $this->client->request('GET', 'https://www.googleapis.com/books/v1/volumes/'. $id . '?key=' . $apiKey);

        if($reponse->getStatusCode() == 200){
            $book = $reponse->toArray();
        }
        dump($book);
        return $this->render("book/show.html.twig",[
            'book' => $book,
        ]);
    }
}
