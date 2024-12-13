<?php

namespace App\Tests\Entity;

use App\Entity\Film;
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

    public function testGetFilms(): void
    {
        $genre = new Genre();
        $this->assertCount(0, $genre->getFilms());
    }


    public function testAddFilm(): void
    {
        $genre = new Genre();
        $film = new Film();
        $genre->addFilm($film);

        $this->assertCount(1, $genre->getFilms());
        $this->assertSame($genre, $film->getGenre());
    }

    public function testRemoveFilm(): void
    {
        $genre = new Genre();
        $film = new Film();
        $genre->addFilm($film);
        $genre->removeFilm($film);

        $this->assertCount(0, $genre->getFilms());
        $this->assertNull($film->getGenre());
    }
}
