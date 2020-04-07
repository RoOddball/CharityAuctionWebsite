<?php

namespace App\Repository;

use App\Entity\IsBid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IsBid|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsBid|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsBid[]    findAll()
 * @method IsBid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsBidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IsBid::class);
    }

    // /**
    //  * @return IsBid[] Returns an array of IsBid objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IsBid
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
