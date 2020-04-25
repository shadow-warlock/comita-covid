<?php

namespace App\Repository;

use App\Entity\MeetSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MeetSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetSlot[]    findAll()
 * @method MeetSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetSlot::class);
    }

    // /**
    //  * @return MeetSlot[] Returns an array of MeetSlot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MeetSlot
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
