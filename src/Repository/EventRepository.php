<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
    public function findEventsInRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.date >= :startDate')
            ->andWhere('e.date <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }
public function countEventsafterdate(\DateTimeInterface $date): int
{
    return $this->createQueryBuilder('e')
        ->select('count(e.id)')
        ->where('e.date >= :Date')
        ->setParameter('Date', $date)
        ->getQuery()
        ->getSingleScalarResult();
}
public function countEvent():int
{
    return $this->createQueryBuilder('e')
        ->select('count(e.id)')
        ->getQuery()
        ->getSingleScalarResult();
}
public function stat()
{
    return $this->createQueryBuilder('e')
        ->select(
            'e.id AS eventId',
            'e.name',
            'e.thumbnail',
            'COALESCE(COUNT(r.id) * e.prix, 0) AS totalRevenue'
        )
        ->leftJoin('e.reservations', 'r')
        ->where('r.statut = :acceptedStatus')
        ->setParameter('acceptedStatus', 'accepted')
        ->groupBy('e.id', 'e.prix', 'e.thumbnail')
        ->orderBy('totalRevenue', 'DESC')
        ->getQuery()
        ->getResult();
}


    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
