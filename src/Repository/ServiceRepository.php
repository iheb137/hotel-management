<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }
    public function countServices():int
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function stat()
    {
        return $this->createQueryBuilder('s')
            ->select(
                's.id AS serviceId',
                's.nom',
                's.thumbnail',
                'COUNT(r.id) AS reservationCount',
                'COALESCE(COUNT(r.id) * s.prix, 0) AS totalRevenue'
            )
            ->leftJoin('s.reservations', 'r')
            ->where('r.statut = :acceptedStatus')
            ->setParameter('acceptedStatus', 'accepted')
            ->groupBy('s.id', 's.prix', 's.thumbnail')
            ->orderBy('totalRevenue', 'DESC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
