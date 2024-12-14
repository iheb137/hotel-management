<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\Commentaire;
use App\Entity\Event;
use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testSetAndGetId(): void
    {
        $event = new Event();
        $this->assertNull($event->getId());
    }

    public function testSetAndGetName(): void
    {
        $event = new Event();
        $event->setName('Music Festival');
        $this->assertEquals('Music Festival', $event->getName());
    }

    public function testSetAndGetPrix(): void
    {
        $event = new Event();
        $event->setPrix(99.99);
        $this->assertEquals(99.99, $event->getPrix());
    }

    public function testSetAndGetDate(): void
    {
        $event = new Event();
        $date = new \DateTime('2024-12-25');
        $event->setDate($date);
        $this->assertSame($date, $event->getDate());
    }

    public function testSetAndGetDescription(): void
    {
        $event = new Event();
        $event->setDescription('An exciting music festival!');
        $this->assertEquals('An exciting music festival!', $event->getDescription());
    }

    public function testSetAndGetThumbnail(): void
    {
        $event = new Event();
        $event->setThumbnail('festival.jpg');
        $this->assertEquals('festival.jpg', $event->getThumbnail());
    }

    public function testAddAndRemoveCommentaire(): void
    {
        $event = new Event();
        $commentaire = new Commentaire();

        $event->addCommentaire($commentaire);
        $this->assertTrue($event->getCommentaires()->contains($commentaire));
        $this->assertSame($event, $commentaire->getEvent());

        $event->removeCommentaire($commentaire);
        $this->assertFalse($event->getCommentaires()->contains($commentaire));
        $this->assertNull($commentaire->getEvent());
    }

    public function testAddAndRemoveReservation(): void
    {
        $event = new Event();
        $reservation = new Reservation();

        $event->addReservation($reservation);
        $this->assertTrue($event->getReservations()->contains($reservation));
        $this->assertTrue($reservation->getEvents()->contains($event));

        $event->removeReservation($reservation);
        $this->assertFalse($event->getReservations()->contains($reservation));
        $this->assertFalse($reservation->getEvents()->contains($event));
    }
}
