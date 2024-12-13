<?php

namespace App\Tests\Entity;

use App\Entity\Cinema;
use App\Entity\Qualite;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Entity\Siege;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Salle::class)]
class SalleTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $salle = new Salle();
        $this->assertNull($salle->getId());
    }

    #[Test]
    public function testGetSetCinema()
    {
        $salle = new Salle();
        $cinema = new Cinema();
        $salle->setCinema($cinema);
        $this->assertSame($cinema, $salle->getCinema());
    }

    #[Test]
    public function testGetSetNbPlace()
    {
        $salle = new Salle();
        $nbPlace = 100;
        $salle->setNbPlace($nbPlace);
        $this->assertSame($nbPlace, $salle->getNbPlace());
    }

    #[Test]
    public function testGetSetNumSalle()
    {
        $salle = new Salle();
        $numSalle = 'A1';
        $salle->setNumSalle($numSalle);
        $this->assertSame($numSalle, $salle->getNumSalle());
    }

    #[Test]
    public function testGetSetQualite()
    {
        $salle = new Salle();
        $qualite = new Qualite();
        $salle->setQualite($qualite);
        $this->assertSame($qualite, $salle->getQualite());
    }

    #[Test]
    public function testGetSetNbPlacePmr()
    {
        $salle = new Salle();
        $nbPlacePmr = 10;
        $salle->setNbPlacePmr($nbPlacePmr);
        $this->assertSame($nbPlacePmr, $salle->getNbPlacePmr());
    }

    #[Test]
    public function testGetSetSeance()
    {
        $salle = new Salle();
        $seance = new Seance();
        $salle->addSeance($seance);
        $this->assertCount(1, $salle->getSeances());
        $salle->removeSeance($seance);
        $this->assertCount(0, $salle->getSeances());
    }

    #[Test]
    public function testGetSetSiege()
    {
        $salles = new Salle();
        $siege = new Siege();
        $salles->addSiege($siege);
        $this->assertCount(1, $salles->getSieges());
        $salles->removeSiege($siege);
        $this->assertCount(0, $salles->getSieges());
    }
}
