<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
    public function search(Request $request): Response
    {
        // recupération du critère de recherche
        $keywords = $request->query->get('q');

        // on recupère l'info case a coche free
        $isFree = $request->query->get('free');
        $param = !empty($isFree)? '&filter=free-ebook':'';
        dump($param);

        //clé api
        $apiKey = 'AIzaSyCYCOR-Vs-22O1A-kx1hr1k2eH4g0k--VI';
        
        //envoie de la requète vers l'api google book
        $reponse =$this->client->request('GET', 'https://www.googleapis.com/books/v1/volumes?q='.$keywords.$param.'&key='.$apiKey);

        if($reponse->getStatusCode() == 200)
        {
            $content = $reponse->toArray();
            $books = $content['items'];

            return new JsonResponse([
                'content' => $this->renderView('book/_content.html.twig',['books' => $books])
            ]);
        }
        

        return $this->render("book/search.html.twig", [
            'controller_name' => 'BookController',
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
