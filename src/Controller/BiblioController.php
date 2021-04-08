<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\Cover;
use App\Entity\Writer;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class BiblioController extends AbstractController
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @Route("/biblio", name="index_biblio")
     */
    public function index(): Response
    {
        //utilisateur connecté
        /** @var User $user */
        $user = $this->getUser();

        $biblio = $user->getBiblio();
        $books = $biblio->getBooks();


        return $this->render('biblio/index.html.twig', compact('books', 'biblio'));
    }

    /**
     * @Route("/biblio/add", name="add_book_biblio")
     */
    public function addBooktoBilio(Request $request, WriterRepository $writerRepository ,EntityManagerInterface $em):Response
    {

        /** @var User $user */
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('warning','vous devez être connecté pour réaliser cette action');
            return $this->redirectToRoute('index_biblio');
        }

        $book = new Book();

        $form = $this->createForm(BookType::class,$book);

        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){

            $cover = new Cover();
            $image = $form->get('cover')->getData();
            if ($image != null) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('cover_directory'), $fichier);
                $cover->setFileName($fichier);
                $em->persist($cover);
            }

            $book->setEditor($user)
                    ->setSlug($this->slugger->slug($book->getTitle()))
                    ->setAddedAt(new \DateTime())
                    ->addBiblio($user->getBiblio())
                    ->setIsApiBook(false)
                    ->setCover($cover);

            $em->persist($book);
            $em->flush();
            
            $this->addFlash('success','Livre ajouté à votre bibliothèque');
            return $this->redirectToRoute('index_biblio');
        }

        
        
       

        return $this->render('biblio/add.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/biblio/{id}/remove", name="remove_book_biblio")
     */
    public function removeBookFromBiblio(Book $book, Request $request, EntityManagerInterface $em, BookRepository $br):Response
    {
        if(!$book){
            $this->addFlash('danger','Livre introuvable');
            return $this->redirectToRoute('index_biblio');
        }

        /*on verifie que l'utilisateur connecté est peut enlever le livre de cet biblio*/
        if (!$this->isGranted('manage', $book)) {
            $this->addFlash('danger', 'action non autorisé');
            return $this->redirectToRoute('index_biblio');
        }

        /** @var User $user */
        $user = $this->getUser();

        $biblio = $user->getBiblio();

        //suppression du livre de la bibliothèque
        $biblio->removeBook($book);
        $em->persist($biblio);
        $em->persist($book);

        $em->flush();

        return $this->json([
            'status' => 200,
            'message' => 'Livre retiré de la bibliothèque'
        ], 200);
    }


    
}
