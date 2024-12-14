<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\Reclamation;
use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;

class ReclamationTest extends TestCase
{
    public function testSetAndGetId(): void
    {
        $reclamation = new Reclamation();
        $this->assertNull($reclamation->getId());
    }

    public function testSetAndGetNom(): void
    {
        $reclamation = new Reclamation();
        $reclamation->setNom('Complaint Name');

        $this->assertEquals('Complaint Name', $reclamation->getNom());
    }

    public function testSetAndGetTexte(): void
    {
        $reclamation = new Reclamation();
        $reclamation->setTexte('This is a test complaint.');

        $this->assertEquals('This is a test complaint.', $reclamation->getTexte());
    }

    public function testSetAndGetReservation(): void
    {
        $reclamation = new Reclamation();
        $reservation = new Reservation();

        $reclamation->setReservation($reservation);

        $this->assertSame($reservation, $reclamation->getReservation());
    }
}
