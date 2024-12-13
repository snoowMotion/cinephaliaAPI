<?php

namespace App\Tests\Entity;

use App\Entity\Salle;
use App\Entity\Siege;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Siege::class)]
class SiegeTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $siege = new Siege();
        $this->assertNull($siege->getId());
    }

    #[Test]
    public function testGetSetNumero()
    {
        $siege = new Siege();
        $numero = 'A1';
        $siege->setNumero($numero);
        $this->assertSame($numero, $siege->getNumero());
    }

    #[Test]
    public function testGetSetSalle()
    {
        $siege = new Siege();
        $salle = new Salle();
        $siege->setSalle($salle);
        $this->assertSame($salle, $siege->getSalle());
    }

    #[Test]
    public function testGetSetPMR()
    {
        $siege = new Siege();
        $isPMR = true;
        $siege->setPMR($isPMR);
        $this->assertSame($isPMR, $siege->isPMR());
    }
}
