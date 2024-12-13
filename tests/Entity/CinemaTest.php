<?php

namespace App\Tests\Entity;

use App\Entity\Cinema;
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
}
