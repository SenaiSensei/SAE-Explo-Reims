<?php

namespace App\Repository;

use App\Entity\Itinerary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Itinerary>
 */
class ItineraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Itinerary::class);
    }

    /**
     * @return Itinerary|null
     *
     * get
     */
    public function findWithPlace(int $id): ?Itinerary
    {
        $req = $this->createQueryBuilder('i')
            ->Select('i,po as pathOrder,p as place,c as category')
            ->leftJoin('i.pathOrder', 'po')
            ->leftJoin('po.place', 'p')
            ->leftJoin('p.category', 'c')
            ->andWhere('i.id = :id')
            ->setParameter('id', $id)
            ->OrderBy('po.position', 'ASC');

        return $req->getQuery()->getOneOrNullResult();
    }

    public function countPlaceForOne(int $id): int
    {
        $req = $this->createQueryBuilder('i')
            ->Select('Count(p)')
            ->leftJoin('i.pathOrder', 'po')
            ->leftJoin('po.place', 'p')
            ->GroupBy('i.id')
            ->andWhere('i.id = :id')
            ->setParameter('id', $id)
            ->OrderBy('po.position', 'ASC');

        return $req->getQuery()->getOneOrNullResult()[1];
    }

    /**
     * Finds the itinerary with the highest average note.
     *
     * @return Itinerary|null the itinerary with the highest average note, or null if not found
     */
    public function findItineraryWithHighestNote(): ?Itinerary
    {
        $req = $this->createQueryBuilder('i')
            ->select('i')
            ->orderBy('i.note', 'DESC')
            ->setMaxResults(1);

        return $req->getQuery()->getOneOrNullResult();
    }
    //    /**
    //     * @return Itinerary[] Returns an array of Itinerary objects
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

    //    public function findOneBySomeField($value): ?Itinerary
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
