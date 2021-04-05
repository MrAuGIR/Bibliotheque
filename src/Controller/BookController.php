<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Cover;
use App\Entity\PublishingHouse;
use App\Entity\User;
use App\Entity\Writer;
use App\Entity\Biblio;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

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

        $user = $this->getUser();

        $books = $user->getBiblio()->getBooks();

        return $this->render('book/index.html.twig', [
            'books' => $books,
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

    /**
     * ajout dans la biblio de l'utilisateur d'un livre depuis l'api google book
     * @IsGranted("ROLE_USER")
     * @Route("/book/add/apibook/{id}", name="add_apibook_biblio", methods={"GET","POST"})
     */
    public function add($id, SluggerInterface $slugger, EntityManagerInterface $em, BookRepository $bookRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        //si pas utilisateur
        if(!$user){
            return $this->json([
                'status' => 403,
                'message'=> 'non autorisé'
            ],403);
        }

        //le livre existe t-il déjà dans la bdd ?
        $book = $bookRepository->findOneBy(['apiId' => $id]);

        //si oui est-il dans la biblio de l'utilisateur ?
        if($book){

            //biblio de l'utilisateur
            /** @var Biblio $biblio  */
            $biblio = $user->getBiblio();

            //dans le cas ou le livre est déjà dans la biblio de l'utilisateur
            if ($user->alreadyAddbyUser($id)) {
                
                //on supprime le livre de la bibliothèque de l'utilisateur
                $biblio->removeBook($book);
                
                $em->persist($biblio);
                
                //si le livre n'est pas présent dans d'autre biliothèque on le supprime de la base de donnée
                if (empty($book->getBiblios())) {

                    $em->remove($book);
                }

                $em->flush();

                return $this->json([
                    'status' => 200,
                    'message' => 'livre supprimé de la bibliothèque'
                ], 200);
            }

            //dans le cas ou le livre existe mais n'est pas dans la biblio de l'utilisateur
            $biblio->addBook($book);

            $em->persist($biblio);
            $em->flush();

            return $this->json([
                'status' => 200,
                'message' => 'livre ajouté à la bibliothèque'
            ], 200);

        }

        //dernier cas -> creation du livre et ajout a la biblio de l'utilisateur

        //clé api
        $apiKey = 'AIzaSyCYCOR-Vs-22O1A-kx1hr1k2eH4g0k--VI';
        $reponse = $this->client->request('GET', 'https://www.googleapis.com/books/v1/volumes/' . $id . '?key=' . $apiKey);

        $bookInfo = $reponse->toArray();

        
        //creation du livre
        $book = new Book();
        $book->setAddedAt(new \DateTime())
            ->setIsApiBook(true)
            ->setApiId($id)
            ->setTitle($bookInfo['volumeInfo']['title'])
            ->setDescription(isset($bookInfo['volumeInfo']['description'])? $bookInfo['volumeInfo']['description'] : "pas de description pour ce produit" )
            ->addBiblio($user->getBiblio());
        if(array_key_exists('industryIdentifiers', $bookInfo['volumeInfo'])){
            $book->setISBN($bookInfo['volumeInfo']['industryIdentifiers'][1]['identifier']);
        }else{
            $book->setISBN('pas de donnée');
        }
        
        if(array_key_exists('publishedDate', $bookInfo['volumeInfo'])){
            $book->setPublishedAt(new \DateTime($bookInfo['volumeInfo']['publishedDate']));
        }
        
        $book->setSlug($slugger->slug($bookInfo['volumeInfo']['title']));
        
        //si les infos des auteurs du livres existe
        if(array_key_exists('authors',$bookInfo['volumeInfo'])){
            foreach ($bookInfo['volumeInfo']['authors'] as $infoAuthor) {
                $author = new Writer();
                $author->setLastName($infoAuthor)
                    ->setSlug($slugger->slug($infoAuthor));
                $book->addWriter($author);

                $em->persist($author);
            }
        }

        //si les covers du livre existe
        $cover = new Cover();
        if(array_key_exists('imageLinks',$bookInfo['volumeInfo'])){
            
            if(!empty($bookInfo['volumeInfo']['imageLinks']['thumbnail'])){
                $cover->setPathFile($bookInfo['volumeInfo']['imageLinks']['thumbnail']);
            }elseif(!empty($bookInfo['volumeInfo']['imageLinks']['smallThumbnail'])) {
                $cover->setPathFile($bookInfo['volumeInfo']['imageLinks']['smallThumbnail']);
            }else{
                $cover->setPathFile('/images/covers/default.jpg');
            }
            $cover->setBook($book)
                ->setFileName('default');
            
        }else{
            $cover->setBook($book)
                ->setPathFile('/images/covers/default.jpg')
                ->setFileName('default');
        }
        $book->setCover($cover);
        $em->persist($cover);



        //la maison d'édition
        $publishingHouse = new PublishingHouse();
        if (array_key_exists('publisher', $bookInfo['volumeInfo'])) {
            $publishingHouse->setName($bookInfo['volumeInfo']['publisher']);
        }else{
            $publishingHouse->setName('editeur inconnue');
        }
        $book->setPublishingHouse($publishingHouse);
        $em->persist($publishingHouse);

        $em->persist($book);
        
        $em->flush();

        return $this->json([
            'status' => 200,
            'message' => 'livre ajouté à la bibliotèque'
        ], 200);
    }
}
