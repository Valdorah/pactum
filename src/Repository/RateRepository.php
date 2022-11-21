<?php

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function getMaxOfDealMark(int $userId){
        // SELECT MAX(nb)
        // FROM (SELECT SUM(rate_mark) as nb
        //         FROM RATE r
        //         INNER JOIN DEAL d ON r.rate_deal = d.deal_id
        //         WHERE d.deal_user_id = 1
        //         GROUP BY rate_deal
        //     ) AS t
        $qb = $this->createQueryBuilder('m')
            ->select('SUM(m.mark) nb')
            ->leftJoin('m.deal', 'd')
            ->where('d.user = :id')
            ->orderBy('nb', 'DESC')
            ->groupBy('m.deal')
            ->setParameter(':id', $userId)
        ;
        
        // $qb = $this->createQueryBuilder('m')
        //     ->select('MAX(nb)')
        //     ->from('SELECT SUM(m.rate_mark) nb
        //         FROM App:Rate r, App:Deal d
        //         WHERE d.user = :id
        //         GROUP BY m.rate_deal',1
        //     )
        //     ->setParameter(':id', $userId)
        // ;

        return $qb->getQuery()->getResult();
    }

    public function getAvgOfDealMark(int $userId){
        // SELECT AVG(nb)
        // FROM (SELECT SUM(rate_mark) as nb
        //     FROM RATE r
        //     INNER JOIN DEAL d ON r.rate_deal = d.deal_id
        //     WHERE d.deal_user_id = 1
        //     AND d.deal_posted_at BETWEEN ADDDATE(NOW(), -1) AND NOW()
        //     GROUP BY rate_deal
        // ) AS t
        
        $qb = $this->createQueryBuilder('r')
            ->select('SUM(r.mark)')
            ->leftJoin('r.deal', 'd')
            ->where('d.user = :id')
            ->andWhere('d.posted_at BETWEEN CURRENT_TIME()-365 AND CURRENT_TIME()')
            ->groupBy('r.deal')
            ->setParameter('id', $userId)
        ;

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Rate[] Returns an array of Rate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rate
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
