<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\Cover;
use App\Entity\Writer;
use App\Form\BookType;
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
    public function add(Request $request, WriterRepository $writerRepository ,EntityManagerInterface $em):Response
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

        if($form->isSubmitted()){

            if($form->isValid()){

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

            $this->addFlash('warning', 'mauvaise saisie des valeurs');
            return $this->redirectToRoute('add_book_biblio');
        }
       

        return $this->render('biblio/add.html.twig',[
            'form' => $form->createView()
        ]);
    }


    
}
