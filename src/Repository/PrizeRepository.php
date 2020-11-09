<?php

namespace App\Repository;

use App\Entity\Prize;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prize|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prize|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prize[]    findAll()
 * @method Prize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrizeRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prize::class);
    }

    public function getMoneySum(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COALESCE(SUM(p.money), 0) as sum')
            ->andWhere('p.user IS NOT NULL')
            ->andWhere('p.status = :status')
            ->andWhere('p.type = :type')
            ->setParameter('type', Prize::TYPE_MONEY)
            ->setParameter('status', Prize::STATUS_ISSUED)
            ->getQuery()
            ->getSingleResult()['sum'];
    }

    public function getUserMoneySum(User $user): int
    {
        return $this->createQueryBuilder('p')
            ->select('COALESCE(SUM(p.money), 0) as sum')
            ->andWhere('p.user = :user')
            ->andWhere('p.status = :status')
            ->andWhere('p.type = :type')
            ->setParameter('user', $user)
            ->setParameter('type', Prize::TYPE_MONEY)
            ->setParameter('status', Prize::STATUS_ISSUED)
            ->getQuery()
            ->getSingleResult()['sum'];
    }


    public function getUserScoresSum(User $user): int
    {
        return $this->createQueryBuilder('p')
            ->select('COALESCE(SUM(p.scores), 0) as sum')
            ->andWhere('p.user = :user')
            ->andWhere('p.status = :status')
            ->andWhere('p.type = :type')
            ->setParameter('user', $user)
            ->setParameter('status', Prize::STATUS_ISSUED)
            ->setParameter('type', Prize::TYPE_SCORES)
            ->getQuery()
            ->getSingleResult()['sum'];
    }

    // /**
    //  * @return Prize[] Returns an array of Prize objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Prize
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
