<?php

namespace App\Tests\Entity;

use App\Entity\Film;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\Seance;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Seance::class)]
class SeanceTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $seance = new Seance();
        $this->assertNull($seance->getId());
    }

    #[Test]
    public function testGetSetSalle()
    {
        $seance = new Seance();
        $salle = new Salle();
        $seance->setSalle($salle);
        $this->assertSame($salle, $seance->getSalle());
    }

    #[Test]
    public function testGetSetDateDebut()
    {
        $seance = new Seance();
        $dateDebut = new \DateTime();
        $seance->setDateDebut($dateDebut);
        $this->assertSame($dateDebut, $seance->getDateDebut());
    }

    #[Test]
    public function testGetSetDateFin()
    {
        $seance = new Seance();
        $dateFin = new \DateTime();
        $seance->setDateFin($dateFin);
        $this->assertSame($dateFin, $seance->getDateFin());
    }

    #[Test]
    public function testGetSetFilm()
    {
        $seance = new Seance();
        $film = new Film();
        $seance->setFilm($film);
        $this->assertSame($film, $seance->getFilm());
    }

    #[Test]
    public function testGetSetReservation()
    {
        $seance = new Seance();
        $reservation = new Reservation();
        $seance->addReservation($reservation);
        $this->assertCount(1, $seance->getReservations());
        $seance->removeReservation($reservation);
        $this->assertCount(0, $seance->getReservations());
    }
}
