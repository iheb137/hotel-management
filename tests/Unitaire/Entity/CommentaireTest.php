<?php

namespace App\Tests\Unitaire\Entity;

use App\Entity\Commentaire;
use App\Entity\Event;
use App\Entity\Room;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentaireTest extends TestCase
{
    public function testSetAndGetId(): void
    {
        $commentaire = new Commentaire();
        $this->assertNull($commentaire->getId());
    }

    public function testSetAndGetTexte(): void
    {
        $commentaire = new Commentaire();
        $commentaire->setTexte('This is a test comment.');
        $this->assertEquals('This is a test comment.', $commentaire->getTexte());
    }

    public function testSetAndGetUser(): void
    {
        $commentaire = new Commentaire();
        $user = new User();

        $commentaire->setUser($user);
        $this->assertSame($user, $commentaire->getUser());
    }

    public function testSetAndGetRoom(): void
    {
        $commentaire = new Commentaire();
        $room = new Room();

        $commentaire->setRoom($room);
        $this->assertSame($room, $commentaire->getRoom());
    }

    public function testSetAndGetEvent(): void
    {
        $commentaire = new Commentaire();
        $event = new Event();

        $commentaire->setEvent($event);
        $this->assertSame($event, $commentaire->getEvent());
    }
}
