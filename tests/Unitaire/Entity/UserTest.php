<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetAndSetEmail(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');

        $this->assertEquals('user@example.com', $user->getEmail());
    }

    public function testGetAndSetPassword(): void
    {
        $user = new User();
        $user->setPassword('hashed_password');

        $this->assertEquals('hashed_password', $user->getPassword());
    }



    public function testUserIdentifier(): void
    {
        $user = new User();
        $user->setEmail('unique_user@example.com');

        $this->assertEquals('unique_user@example.com', $user->getUserIdentifier());
    }

    public function testAddReservation(): void
    {
        $user = new User();
        $mockReservation = $this->createMock(\App\Entity\Reservation::class);

        $user->addReservation($mockReservation);

        $this->assertTrue($user->getReservations()->contains($mockReservation));
    }

    public function testAddCommentaire(): void
    {
        $user = new User();
        $mockCommentaire = $this->createMock(\App\Entity\Commentaire::class);

        $user->addCommentaire($mockCommentaire);

        $this->assertTrue($user->getCommentaires()->contains($mockCommentaire));
    }
}
