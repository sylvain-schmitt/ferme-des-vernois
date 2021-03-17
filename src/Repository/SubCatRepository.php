<?php

namespace App\Repository;

use App\Entity\SubCat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubCat|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubCat|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubCat[]    findAll()
 * @method SubCat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubCatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubCat::class);
    }

    // /**
    //  * @return SubCat[] Returns an array of SubCat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubCat
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
