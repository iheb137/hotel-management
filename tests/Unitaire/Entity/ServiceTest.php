<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testSetAndGetName(): void
    {
        $service = new Service();
        $service->setNom('Room Cleaning');

        $this->assertEquals('Room Cleaning', $service->getNom());
    }

    public function testSetAndGetDescription(): void
    {
        $service = new Service();
        $service->setDescription('Provides room cleaning services for hotel guests.');

        $this->assertEquals('Provides room cleaning services for hotel guests.', $service->getDescription());
    }

    public function testSetAndGetPrice(): void
    {
        $service = new Service();
        $service->setPrix(99.99);

        $this->assertEquals(99.99, $service->getPrix());
    }




}
