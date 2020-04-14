<?php

namespace App\Repository;

use App\Entity\Bid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bid[]    findAll()
 * @method Bid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bid::class);
    }

     /**
      * @return Bid[] Returns an array of Bid objects
      */

    public function findByUser($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Bid[] Returns an array of Bid objects
     */

    public function findByAuction($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.auction = :val')
            ->setParameter('val', $value)
            ->orderBy('b.ammount', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOneBySomeField($userID,$auctionID): ?Bid
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val1')
            ->andWhere('b.auction = :val2')
            ->setParameter('val1', $userID)
            ->setParameter('val2', $auctionID)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
