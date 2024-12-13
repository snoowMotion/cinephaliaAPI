<?php

namespace App\Tests\Entity;

use App\Entity\Qualite;
use App\Entity\Salle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Qualite::class)]
class QualiteTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $qualite = new Qualite();
        $this->assertNull($qualite->getId());
    }

    #[Test]
    public function testGetSetLibelle()
    {
        $qualite = new Qualite();
        $libelle = 'HD';
        $qualite->setLibelle($libelle);
        $this->assertSame($libelle, $qualite->getLibelle());
    }

    #[Test]
    public function testGetSetPrix()
    {
        $qualite = new Qualite();
        $prix = 100;
        $qualite->setPrix($prix);
        $this->assertSame($prix, $qualite->getPrix());
    }

    #[Test]
    public function testGetSalles()
    {
        $qualite = new Qualite();
        $qualite->addSalle(new Salle());
        $this->assertCount(1, $qualite->getSalles());
    }

    #[Test]
    public function testAddSalle()
    {
        $qualite = new Qualite();
        $salle = new Salle();
        $qualite->addSalle($salle);
        $this->assertTrue($qualite->getSalles()->contains($salle));
        $this->assertSame($qualite, $salle->getQualite());
    }

    #[Test]
    public function testRemoveSalle()
    {
        $qualite = new Qualite();
        $salle = new Salle();
        $qualite->addSalle($salle);
        $qualite->removeSalle($salle);
        $this->assertFalse($qualite->getSalles()->contains($salle));
        $this->assertNull($salle->getQualite());
    }
}
