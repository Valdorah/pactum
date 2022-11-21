<?php

namespace App\Repository;

use App\Entity\Deal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Deal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deal[]    findAll()
 * @method Deal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deal::class);
    }

    public function getDealsOnLastWeek(){
        return $this->createQueryBuilder('d')
            ->from('App\Entity\Comment', 'c')
            ->leftJoin('c.deal', 'dc')
            ->andWhere('d.posted_at > CURRENT_DATE() - 7')
            ->andWhere('d.expired = false')
            ->orderBy('COUNT(c.id)', 'DESC')
            ->groupBy('d.id')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getLastWeekDeals() {
        $query = $this->getEntityManager()->createQuery('
            SELECT 
                d.id,
                d.title,
                d.description,
                d.url,
                d.price,
                d.normalPrice,
                d.deliveryCost,
                d.discountCode,
                d.image,
                d.posted_at
            FROM App:Deal d
            WHERE d.posted_at > CURRENT_DATE() - 7
            AND d.expired = false
        ');
        return $query->getResult();
    }

    /*public function findHome()
    {
        $qb = $this->createQueryBuilder('d');
        $qb
            ->from('App:Commentaire', 'c')
            ->leftJoin('c.deal', 'dc')
            ->where('d = dc')
            ->andWhere('d.dateDeCreation > CURRENT_DATE() - 7')
            ->orderBy('COUNT(c.id)', 'DESC')
            ->groupBy('d.id')
        ;

        return $qb->getQuery()->getResult();
    }*/

    // /**
    //  * @return Deal[] Returns an array of Deal objects
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
    public function findOneBySomeField($value): ?Deal
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getDealRating(Deal $deal)
    {
        $dealId = $deal->getId();
        $res = $this->getEntityManager()->createQuery("
            SELECT SUM(r.mark) FROM App:Deal d, App:Rate r 
            WHERE d = r.deal 
            AND d.id = $dealId
        ")->getSingleScalarResult();

        return empty($res) ? 0 : $res;
    }

    public function getHotDealsToday($dealType = null)
    {
        $qb = $this->createQueryBuilder('d');

        $today = new \Datetime();
        $today->setTime ( 0, 0, 0 );
        $tomorrow = new \Datetime();
        $tomorrow->setTime ( 0, 0, 0 );
        $tomorrow->add(new \DateInterval("P1D"));

        $qb->where('100 <= (SELECT SUM(r.mark) FROM App:Deal d2, App:Rate r WHERE d2 = r.deal AND d2 = d)')
            ->andwhere('d.posted_at >= :today')
            ->andWhere('d.posted_at < :tomorrow')
            ->andWhere('d.expired = false')
            ->setParameter(':today', $today )
            ->setParameter(':tomorrow', $tomorrow)
            ->orderBy('d.title', 'DESC');

        if (!empty($dealType)) {
            $qb->andWhere('t.typeName = :type');
            $qb->setParameter('type', $dealType);
        }

        return $qb->getQuery()->getResult();
    }

    public function getHotDeals($dealType = null)
    {
        $qb = $this->createQueryBuilder('d');

        $qb->leftJoin('d.type', 't')
            ->where('100 <= (SELECT SUM(r.mark) FROM App:Deal d2, App:Rate r WHERE d2 = r.deal AND d2 = d)')
            ->andWhere('d.expired = false')
            ->orderBy('d.posted_at', 'DESC');

        if (!empty($dealType)) {
            $qb->andWhere('t.typeName = :type');
            $qb->setParameter('type', $dealType);
        }

        return $qb->getQuery()->getResult();
    }

    public function countPostedDeals(int $userId){
        $qb = $this->createQueryBuilder('d')
            ->select('COUNT(d)')
            ->leftJoin('d.user', 'u')
            ->where('u.id = :id')
            ->setParameter(':id', $userId)
        ;

        return $qb->getQuery()->getResult();
    }

    public function countPostedHotDeals(int $userId){
        $qb = $this->createQueryBuilder('d')
            ->select('COUNT(d)')
            ->leftJoin('d.user', 'u')
            ->where('u.id = :id')
            ->andWhere('100 <= (SELECT SUM(r.mark) FROM App:Deal d2, App:Rate r WHERE d2 = r.deal AND d2 = d)')
            ->setParameter(':id', $userId)
        ;

        return $qb->getQuery()->getResult();
    }

    public function getDealContains(string $word, int $mark){
        $qb = $this->createQueryBuilder('d')
            ->where('d.title LIKE :word')
            ->orWhere('d.description LIKE :word')
            ->andWhere('d.expired = false')
            ->andWhere(':mark <= (SELECT SUM(r.mark) FROM App:Deal d2, App:Rate r WHERE d2 = r.deal AND d2 = d)')
            ->setParameters([':word' => "%$word%", ':mark' => $mark])
        ;
        
        return $qb->getQuery()->getResult();
    }
    
    public function search(String $search) {
        $qb = $this->createQueryBuilder('d');
        $qb->andWhere('d.title LIKE :title')
            ->orWhere('d.description LIKE :desc')
            ->andWhere('d.expired = false')
            ->setParameter('title', "%$search%")
            ->setParameter('desc', "%$search%");

        return $qb->getQuery()->getResult();
    }
}
