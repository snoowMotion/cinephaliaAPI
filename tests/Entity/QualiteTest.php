<?php

namespace App\Tests\Entity;

use App\Entity\Qualite;
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
}
