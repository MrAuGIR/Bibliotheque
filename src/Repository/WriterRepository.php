<?php

namespace App\Repository;

use App\Entity\Writer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

/**
 * @method Writer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Writer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Writer[]    findAll()
 * @method Writer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WriterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Writer::class);
    }

    public function findBySearchQuery(string $query, int $limit = 10)
    {
        $query = $this->createQueryBuilder('w');

        $searchTerms = $this->extractSearchTerms($query);
        //si la chaine est vide on retourne un tableau
        if (0 === \count($searchTerms)) {
            return [];
        }

        foreach ($searchTerms as $key => $term) {
            $query->orWhere('w.lastName LIKE :t_'. $key)
            ->setParameter('t_' . $key, '%' . $term . '%');
        }

        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Writer[] Returns an array of Writer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Writer
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Transforme une chaine de caractère de recherche en un tableau de termes à rechercher
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        //on ignore les termes de recherche qui ont moins de 3 caractères
        return array_filter($terms, function ($term) {
            return 2 <= $term->length();
        });
    }
}
