<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Place>
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    /**
     * Retrieves the id, name, latitude, longitude, description, and associated category name
     * of places from the database. Optionally filters the results based on a specific category name.
     *
     * If the $filter parameter is provided, the query filters places to include only those
     * belonging to the specified category. If $filter is empty, all places are returned.
     *
     * @param string $filter The name of the category to filter places by (optional).
     *                       Pass an empty string to retrieve all places without filtering.
     *
     * @return Place[]
     */
    public function findPlaceLocation(string $filter): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.latitude, p.longitude, p.description, ca.name AS categoryName')
            ->leftJoin('p.category', 'ca');
        if (!empty($filter)) {
            $qb
                ->where('ca.name = :filter')
                ->setParameter('filter', $filter);
        }

        return $qb->getQuery()->getResult();
    }

    public function findAllForOne(int $id): ?Place
    {
        return $this->createQueryBuilder('p')
            ->AddSelect('a as accessibility, t as timeTable, c as category, i as image')
            ->LeftJoin('p.accessibility', 'a')
            ->LeftJoin('p.timeTable', 't')
            ->LeftJoin('p.category', 'c')
            ->LeftJoin('p.images', 'i')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNearbyPlaces(float $latitude, float $longitude, float $radius = 5): array
    {
        return $this->createQueryBuilder('p')
            ->select('p,
            (6371 * acos(
                cos(radians(:latitude))
                * cos(radians(p.latitude))
                * cos(radians(p.longitude) - radians(:longitude))
                + sin(radians(:latitude))
                * sin(radians(p.latitude))
            )) AS distance')
            ->having('distance <= :radius')
            ->setParameter('latitude', $latitude)
            ->setParameter('longitude', $longitude)
            ->setParameter('radius', $radius)
            ->orderBy('distance', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchPlaceWithCatg(string $text, int $id = null): array
    {
        $res = $this->createQueryBuilder('p')
            ->Select('p', 'c as category')
            ->Join('p.category', 'c');

        if ($id != null) {
            $res->LeftJoin('p.users', 'u', Expr\Join::WITH, ':id = u.id')
                ->setParameter('id', $id)
                ->andWhere('u is null');
        }

        if (!empty($text)) {
            $res->andWhere('(p.name LIKE :val)')
                ->setParameter('val', '%'.$text.'%');
        }

        return $res->orderBy('p.name', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function searchWithoutOneItinerary(string $text, int $id, int $nbResult = 10): array
    {
        $res = $this->createQueryBuilder('p')
            ->addSelect('p', 'c as category')
            ->Join('p.category', 'c')
            ->leftJoin('p.pathOrder', 'po', Expr\Join::WITH, ':id = po.itinerary')
            ->setParameter('id', $id)
            ->andWhere('po is null')
            ->setMaxResults($nbResult)
        ;
        if (!empty($text)) {
            $res->andWhere('(p.name LIKE :val)')
                ->setParameter('val', '%'.$text.'%');
        }

        return $res->getQuery()
            ->execute();
    }

    /**
     * Retrieves the place with the highest average note from the database.
     *
     * @return array the place with the highest average note, or null if no places exist
     */
    public function getPlaceWithHighestAverageNote(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.name AS name, AVG(n.note) AS avgNote')
            ->leftJoin('p.notes', 'n')
            ->groupBy('p.id')
            ->orderBy('avgNote', 'DESC')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Place[] Returns an array of Place objects
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

    //    public function findOneBySomeField($value): ?Place
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
