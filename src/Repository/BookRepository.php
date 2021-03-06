<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    public function findLastBookPosted(){

        return $this->createQueryBuilder('l')
            ->innerJoin('l.writers','w')
            ->where('l.addedAt <= :now')
            ->orderBy('l.addedAt','DESC')
            ->setParameter('now',new \DateTime())
            ->getQuery()
            ->getResult();

    }


    public function findBySearchQuery(string $query, int $limit = 10){
        $req = $this->createQueryBuilder('s');

        $searchTerms = $this->extractSearchTerms($query);
        //si la chaine est vide on retourne un tableau
        if(0 === \count($searchTerms)){
            return [];
        }

        foreach ($searchTerms as $key => $term) {
            $req->orWhere('s.title LIKE :t_'.$key)
                ->setParameter('t_'.$key,'%'.$term.'%');
        }

        return $req->getQuery()->getResult();

    }
    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Transforme une chaine de caract??re de recherche en un tableau de termes ?? rechercher
     */
    private function extractSearchTerms(string $searchQuery):array
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/',' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        //on ignore les termes de recherche qui ont moins de 3 caract??res
        return array_filter($terms, function($term){
            return 2 <= $term->length();
        });
    }
}
