<?php

namespace App\Repository;

use App\Entity\Catgory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Catgory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catgory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catgory[]    findAll()
 * @method Catgory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatgoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Catgory::class);
    }

    // /**
    //  * @return Catgory[] Returns an array of Catgory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Catgory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
