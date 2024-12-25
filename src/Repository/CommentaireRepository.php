<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\Room;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }
    public function findByRoomId(int $roomId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.room = :roomId')
            ->setParameter('roomId', $roomId)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByEventId(int $eventId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function countRoomComments(int $roomId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.room = :roomId')
            ->andWhere('c.event IS NULL')
            ->setParameter('roomId', $roomId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countEventComments(int $eventId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.event = :eventId')
            ->andWhere('c.room IS NULL')
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Commentaire[] Returns an array of Commentaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commentaire
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
