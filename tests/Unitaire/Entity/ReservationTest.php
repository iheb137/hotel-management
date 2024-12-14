<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\Event;
use App\Entity\Reclamation;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\Service;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    public function testSetAndGetId(): void
    {
        $reservation = new Reservation();
        $this->assertNull($reservation->getId());
    }

    public function testSetAndGetStartDate(): void
    {
        $reservation = new Reservation();
        $startDate = new \DateTime('2024-12-13');
        $reservation->setStartDate($startDate);

        $this->assertEquals($startDate, $reservation->getStartDate());
    }

    public function testSetAndGetEndDate(): void
    {
        $reservation = new Reservation();
        $endDate = new \DateTime('2024-12-20');
        $reservation->setEndDate($endDate);

        $this->assertEquals($endDate, $reservation->getEndDate());
    }

    public function testSetAndGetPrix(): void
    {
        $reservation = new Reservation();
        $reservation->setPrix(250.50);

        $this->assertEquals(250.50, $reservation->getPrix());
    }

    public function testSetAndGetStatut(): void
    {
        $reservation = new Reservation();
        $reservation->setStatut('confirmed');

        $this->assertEquals('confirmed', $reservation->getStatut());
    }

    public function testSetAndGetRoom(): void
    {
        $reservation = new Reservation();
        $room = new Room();
        $reservation->setRoom($room);

        $this->assertSame($room, $reservation->getRoom());
    }

    public function testSetAndGetUser(): void
    {
        $reservation = new Reservation();
        $user = new User();
        $reservation->setUser($user);

        $this->assertSame($user, $reservation->getUser());
    }

    public function testAddAndRemoveReclamation(): void
    {
        $reservation = new Reservation();
        $reclamation = new Reclamation();

        $reservation->addReclamation($reclamation);

        $this->assertCount(1, $reservation->getReclamations());
        $this->assertTrue($reservation->getReclamations()->contains($reclamation));

        $reservation->removeReclamation($reclamation);

        $this->assertCount(0, $reservation->getReclamations());
        $this->assertFalse($reservation->getReclamations()->contains($reclamation));
    }

    public function testAddAndRemoveService(): void
    {
        $reservation = new Reservation();
        $service = new Service();

        $reservation->addService($service);

        $this->assertCount(1, $reservation->getServices());
        $this->assertTrue($reservation->getServices()->contains($service));

        $reservation->removeService($service);

        $this->assertCount(0, $reservation->getServices());
        $this->assertFalse($reservation->getServices()->contains($service));
    }

    public function testAddAndRemoveEvent(): void
    {
        $reservation = new Reservation();
        $event = new Event();

        $reservation->addEvent($event);

        $this->assertCount(1, $reservation->getEvents());
        $this->assertTrue($reservation->getEvents()->contains($event));

        $reservation->removeEvent($event);

        $this->assertCount(0, $reservation->getEvents());
        $this->assertFalse($reservation->getEvents()->contains($event));
    }
}
