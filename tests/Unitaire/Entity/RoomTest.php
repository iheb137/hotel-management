<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\Commentaire;
use App\Entity\Reservation;
use App\Entity\Room;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    public function testSetAndGetId(): void
    {
        $room = new Room();
        $room->setId(1);

        $this->assertEquals(1, $room->getId());
    }

    public function testSetAndGetName(): void
    {
        $room = new Room();
        $room->setName('Deluxe Room');

        $this->assertEquals('Deluxe Room', $room->getName());
    }

    public function testSetAndGetBedNbr(): void
    {
        $room = new Room();
        $room->setBedNbr(2);

        $this->assertEquals(2, $room->getBedNbr());
    }

    public function testSetAndGetPrix(): void
    {
        $room = new Room();
        $room->setPrix(150.00);

        $this->assertEquals(150.00, $room->getPrix());
    }

    public function testSetAndGetDescription(): void
    {
        $room = new Room();
        $room->setDescription('A luxurious room with a sea view.');

        $this->assertEquals('A luxurious room with a sea view.', $room->getDescription());
    }

    public function testSetAndGetThumbnail(): void
    {
        $room = new Room();
        $room->setThumbnail('thumbnail.jpg');

        $this->assertEquals('thumbnail.jpg', $room->getThumbnail());
    }

    public function testAddAndRemoveReservation(): void
    {
        $room = new Room();
        $reservation = new Reservation();

        $room->addReservation($reservation);

        $this->assertCount(1, $room->getReservations());
        $this->assertTrue($room->getReservations()->contains($reservation));

        $room->removeReservation($reservation);

        $this->assertCount(0, $room->getReservations());
        $this->assertFalse($room->getReservations()->contains($reservation));
    }

    public function testAddAndRemoveCommentaire(): void
    {
        $room = new Room();
        $commentaire = new Commentaire();

        $room->addCommentaire($commentaire);

        $this->assertCount(1, $room->getCommentaires());
        $this->assertTrue($room->getCommentaires()->contains($commentaire));

        $room->removeCommentaire($commentaire);

        $this->assertCount(0, $room->getCommentaires());
        $this->assertFalse($room->getCommentaires()->contains($commentaire));
    }
}
