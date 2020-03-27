<?php

namespace App\Repository;

use App\Entity\Meet;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Meet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meet[]    findAll()
 * @method Meet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meet::class);
    }

    /**
     * @param DateTime $date
     * @return Meet[] Returns an array of Meet objects
     */

    public function deleteByDate(DateTime $date)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.date < :val')
            ->setParameter('val', $date)
            ->delete()
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Meet
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
