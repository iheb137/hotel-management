<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rooms = [
            [
                'title' => 'Chambre Deluxe',
                'price' => 150,
                'image' => 'uploads/rooms/room1.jpg',
            ],
            [
                'title' => 'Suite Luxe',
                'price' => 250,
                'image' => 'uploads/rooms/room2.jpg',
            ],
            [
                'title' => 'Chambre Standard',
                'price' => 100,
                'image' => 'uploads/rooms/room3.jpg',
            ],
        ];

        foreach ($rooms as $data) {
            $room = new Room();
            $room->setName($data['title']);
            $room->setPrix($data['price']);
            $room->setImage($data['image']);

            $manager->persist($room);
        }

        $manager->flush();
    }
}
