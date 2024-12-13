<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SalleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[ORM\Table(name: 'salle', schema: 'cinephaliaapi')]
#[ApiResource]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'salles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cinema $cinema = null;

    #[ORM\Column]
    private ?int $nbPlace = null;

    #[ORM\Column(length: 255)]
    private ?string $numSalle = null;

    #[ORM\ManyToOne(inversedBy: 'salles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Qualite $qualite = null;

    #[ORM\Column]
    private ?int $nbPlacePmr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): static
    {
        $this->cinema = $cinema;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): static
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getNumSalle(): ?string
    {
        return $this->numSalle;
    }

    public function setNumSalle(string $numSalle): static
    {
        $this->numSalle = $numSalle;

        return $this;
    }

    public function getQualite(): ?Qualite
    {
        return $this->qualite;
    }

    public function setQualite(?Qualite $qualite): static
    {
        $this->qualite = $qualite;

        return $this;
    }

    public function getNbPlacePmr(): ?int
    {
        return $this->nbPlacePmr;
    }

    public function setNbPlacePmr(int $nbPlacePmr): static
    {
        $this->nbPlacePmr = $nbPlacePmr;

        return $this;
    }
}
