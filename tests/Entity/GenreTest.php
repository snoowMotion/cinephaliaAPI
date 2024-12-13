<?php

namespace App\Tests\Entity;

use App\Entity\Genre;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Genre::class)]
class GenreTest extends TestCase
{
    public function testGetSetId(): void
    {
        $genre = new Genre();
        $this->assertNull($genre->getId());
    }

    public function testGetSetLibelle(): void
    {
        $genre = new Genre();
        $libelle = 'Action';
        $genre->setLibelle($libelle);
        $this->assertSame($libelle, $genre->getLibelle());
    }
}
