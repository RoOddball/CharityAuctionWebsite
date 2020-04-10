<?php

namespace App\Repository;

use App\Entity\Auction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Auction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auction[]    findAll()
 * @method Auction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auction::class);
    }

    /**
     * @return Auction[] Returns an array of Auction objects
     */

    public function findByExampleField($stateRepository)
    {
//vardump($stateRepository);
        return $this->createQueryBuilder('a')
            ->andWhere('a.state ='. $stateRepository["id"])
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }



    public function findOneByID($value): ?Auction
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}

