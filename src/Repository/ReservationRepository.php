<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }
    public function countServicesInReservations()
    {
        return $this->createQueryBuilder('r')
            ->select('s.name AS serviceName', 'COUNT(s.id) AS serviceCount')
            ->innerJoin('r.services', 's')
            ->groupBy('s.id')
            ->orderBy('serviceCount', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function countReservationsByDate($date)
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id) AS reservationCount')
            ->where('r.date = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    public function totalPrix()
    {
        return $this->createQueryBuilder('r')
            ->select('SUM(r.prix) AS total')
            ->where('r.statut = :statut') // Use a parameterized query
            ->setParameter('statut', 'accepted') // Bind the value to the parameter
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalPrixThisMonth()
    {
        $startOfMonth = new \DateTime('first day of this month midnight'); // Start of the current month
        $endOfMonth = new \DateTime('last day of this month 23:59:59'); // End of the current month

        return $this->createQueryBuilder('r')
            ->select('SUM(r.prix) AS total')
            ->where('r.StartDate BETWEEN :start AND :end') // Use the correct field name with proper case
            ->andWhere('r.statut = :statut ')
            ->setParameter('statut', 'accepted')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }





    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
