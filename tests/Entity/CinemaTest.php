<?php

namespace App\Tests\Entity;

use App\Entity\Cinema;
use App\Entity\Salle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Cinema::class)]
class CinemaTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $cinema = new Cinema();
        $this->assertNull($cinema->getId());
    }

    #[Test]
    public function testGetSetVille()
    {
        $cinema = new Cinema();
        $ville = 'Paris';
        $cinema->setVille($ville);
        $this->assertSame($ville, $cinema->getVille());
    }

    #[Test]
    public function testGetSalles()
    {
        $cinema = new Cinema();
        $cinema->addSalle(new Salle());
        $this->assertCount(1, $cinema->getSalles());
    }

    #[Test]
    public function testAddSalle()
    {
        $cinema = new Cinema();
        $salle = new Salle();
        $cinema->addSalle($salle);
        $this->assertTrue($cinema->getSalles()->contains($salle));
        $this->assertSame($cinema, $salle->getCinema());
    }

    #[Test]
    public function testRemoveSalle()
    {
        $cinema = new Cinema();
        $salle = new Salle();
        $cinema->addSalle($salle);
        $cinema->removeSalle($salle);
        $this->assertFalse($cinema->getSalles()->contains($salle));
        $this->assertNull($salle->getCinema());
    }
}
