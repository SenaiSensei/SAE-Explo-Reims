<?php

namespace App\Repository;

use App\Entity\PathOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PathOrder>
 */
class PathOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PathOrder::class);
    }

    public function findByItineraryId(int $itineraryId): array
    {
        return $this->createQueryBuilder('po')
            ->where('po.itinerary = :itineraryId')
            ->setParameter('itineraryId', $itineraryId)
            ->orderBy('po.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return PathOrder[] Returns an array of PathOrder objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PathOrder
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
