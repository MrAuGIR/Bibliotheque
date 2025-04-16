<?php

namespace App\Repository;

use App\Entity\Biblio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Biblio>
 *
 * @method Biblio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biblio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biblio[]    findAll()
 * @method Biblio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiblioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biblio::class);
    }

    public function getLastBiblioUpdated(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.updatedAt <= :now')
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('b.updatedAt', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function getMostPopularBiblio(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.views > 0')
            ->orderBy('b.views', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function getBiblioByTag(string $code): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.tags', 't')
            ->andWhere('t.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Biblio[] Returns an array of Biblio objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Biblio
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
