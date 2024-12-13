<?php

namespace App\Tests\Entity;

use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Seance;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Film::class)]
class FilmTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $film = new Film();
        $this->assertNull($film->getId());
    }

    #[Test]

    public function testGetSetTitre()
    {
        $film = new Film();
        $titre = 'Inception';
        $film->setTitre($titre);
        $this->assertSame($titre, $film->getTitre());
    }

    #[Test]
    public function testGetSetSynopsis()
    {
        $film = new Film();
        $synopsis = 'A mind-bending thriller';
        $film->setSynopsis($synopsis);
        $this->assertSame($synopsis, $film->getSynopsis());
    }

    #[Test]
    public function testGetSetAfficheUrl()
    {
        $film = new Film();
        $afficheUrl = 'http://example.com/affiche.jpg';
        $film->setAfficheUrl($afficheUrl);
        $this->assertSame($afficheUrl, $film->getAfficheUrl());
    }

    #[Test]
    public function testGetSetAgeMini()
    {
        $film = new Film();
        $ageMini = 13;
        $film->setAgeMini($ageMini);
        $this->assertSame($ageMini, $film->getAgeMini());
    }

    #[Test]
    public function testGetSetLabel()
    {
        $film = new Film();
        $label = 'PG-13';
        $film->setLabel($label);
        $this->assertSame($label, $film->getLabel());
    }

    #[Test]
    public function testGetSetGenre()
    {
        $film = new Film();
        $genre = new Genre();
        $film->setGenre($genre);
        $this->assertSame($genre, $film->getGenre());
    }

    #[Test]
    public function testGetSetSeance()
    {
        $film = new Film();
        $seance = new Seance();
        $film->addSeance($seance);
        $this->assertCount(1, $film->getSeances());
        $film->removeSeance($seance);
        $this->assertCount(0, $film->getSeances());
    }
}
