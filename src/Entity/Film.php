<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FilmRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
#[ORM\Table(name: 'film', schema: 'cinephaliaapi')]
#[ApiResource]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, options: ['default' => ''])]
    private ?string $titre = '';

    #[ORM\Column(length: 2500, options: ['default' => ''])]
    private ?string $synopsis = '';

    #[ORM\Column(length: 255, options: ['default' => ''])]
    private ?string $afficheUrl = '';

    #[ORM\Column(options: ['default' => 0])]
    private ?int $ageMini = 0;

    #[ORM\Column(length: 255, options: ['default' => ''])]
    private ?string $label = '';

    #[ORM\ManyToOne(inversedBy: 'films')]
    private ?Genre $genre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getAfficheUrl(): ?string
    {
        return $this->afficheUrl;
    }

    public function setAfficheUrl(string $afficheUrl): static
    {
        $this->afficheUrl = $afficheUrl;

        return $this;
    }

    public function getAgeMini(): ?int
    {
        return $this->ageMini;
    }

    public function setAgeMini(int $ageMini): static
    {
        $this->ageMini = $ageMini;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }
}
