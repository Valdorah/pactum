<?php

namespace App\Repository;

use App\Entity\DealType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DealType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DealType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DealType[]    findAll()
 * @method DealType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DealTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DealType::class);
    }

    // /**
    //  * @return DealType[] Returns an array of DealType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DealType
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
