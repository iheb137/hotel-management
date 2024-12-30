<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }
public function findByName(string $name)
{
    return $this->findOneBy(['name' => $name]);
}
public function countRooms():int
{
    return $this->createQueryBuilder('r')
        ->select('count(r.id)')
        ->getQuery()
        ->getSingleScalarResult();
}



    public function getRoomReservationStats()
    {
        return $this->createQueryBuilder('r')
            ->select('r.id AS roomId', 'r.name', 'r.Thumbnail', 'COUNT(res.id) AS reservationCount', 'SUM(res.prix) AS totalRevenue')
            ->leftJoin('r.reservations', 'res')
            ->where('res.statut = :acceptedStatus')
            ->setParameter('acceptedStatus', 'accepted')
            ->groupBy('r.id')
            ->orderBy('totalRevenue', 'DESC')
            ->getQuery()
            ->getResult();
    }






}
