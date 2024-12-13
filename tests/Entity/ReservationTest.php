<?php

namespace App\Tests\Entity;

use App\Entity\LinkReservationSiege;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Seance;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Reservation::class)]
class ReservationTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $reservation = new Reservation();
        $this->assertNull($reservation->getId());
    }

    #[Test]
    public function testGetSetClient()
    {
        $reservation = new Reservation();
        $client = new User();
        $reservation->setClient($client);
        $this->assertSame($client, $reservation->getClient());
    }

    #[Test]
    public function testGetSetSeance()
    {
        $reservation = new Reservation();
        $seance = new Seance();
        $reservation->setSeance($seance);
        $this->assertSame($seance, $reservation->getSeance());
    }

    #[Test]
    public function testIsSetFinish()
    {
        $reservation = new Reservation();
        $reservation->setFinish(true);
        $this->assertTrue($reservation->isFinish());
    }

    #[Test]
    public function testGetSetLinkReservationSiege()
    {
        $reservation = new Reservation();
        $createdAt = new LinkReservationSiege();
        $reservation->addLinkReservationSiege($createdAt);
        $this->assertCount(1, $reservation->getLinkReservationSieges());
        $reservation->removeLinkReservationSiege($createdAt);
        $this->assertCount(0, $reservation->getLinkReservationSieges());
    }
}
