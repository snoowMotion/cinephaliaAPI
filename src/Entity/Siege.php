<?php

namespace App\Entity;

use App\Repository\SiegeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiegeRepository::class)]
#[ORM\Table(name: 'siege', schema: 'cinephaliaapi')]
class Siege
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\ManyToOne(inversedBy: 'sieges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salle $salle = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPMR = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function isPMR(): ?bool
    {
        return $this->isPMR;
    }

    public function setPMR(bool $isPMR): static
    {
        $this->isPMR = $isPMR;

        return $this;
    }
}
