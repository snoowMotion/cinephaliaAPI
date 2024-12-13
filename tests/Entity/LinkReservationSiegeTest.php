<?php

namespace App\Tests\Entity;

use App\Entity\LinkReservationSiege;
use App\Entity\Reservation;
use App\Entity\Siege;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LinkReservationSiege::class)]
class LinkReservationSiegeTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $linkReservationSiege = new LinkReservationSiege();
        $this->assertNull($linkReservationSiege->getId());
    }

    #[Test]
    public function testGetSetReservation()
    {
        $linkReservationSiege = new LinkReservationSiege();
        $reservation = new Reservation();
        $linkReservationSiege->setReservation($reservation);
        $this->assertSame($reservation, $linkReservationSiege->getReservation());
    }

    #[Test]
    public function testGetSetSiege()
    {
        $linkReservationSiege = new LinkReservationSiege();
        $siege = new Siege();
        $linkReservationSiege->setSiege($siege);
        $this->assertSame($siege, $linkReservationSiege->getSiege());
    }
}
