<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    // ===============================
    // STAT : SERVICES DANS RESERVATIONS
    // ===============================
    public function countServicesInReservations(): array
    {
        return $this->createQueryBuilder('r')
            ->select('s.name AS serviceName', 'COUNT(s.id) AS serviceCount')
            ->innerJoin('r.services', 's')
            ->groupBy('s.id')
            ->orderBy('serviceCount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // ===============================
    // STAT : RESERVATIONS PAR DATE
    // ===============================
    public function countReservationsByDate(\DateTimeInterface $date): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.startDate <= :date')
            ->andWhere('r.endDate >= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // ===============================
    // STAT : CHIFFRE D'AFFAIRES TOTAL
    // ===============================
    public function totalPrix(): float
    {
        return (float) $this->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.prix), 0)')
            ->where('r.statut = :statut')
            ->setParameter('statut', 'accepted')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // ===============================
    // STAT : CHIFFRE D'AFFAIRES DU MOIS
    // ===============================
    public function totalPrixThisMonth(): float
    {
        $start = new \DateTime('first day of this month 00:00:00');
        $end = new \DateTime('last day of this month 23:59:59');

        return (float) $this->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.prix), 0)')
            ->where('r.startDate BETWEEN :start AND :end')
            ->andWhere('r.statut = :statut')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('statut', 'accepted')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
