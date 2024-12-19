<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[ORM\Table(name: 'salle', schema: 'cinephaliaapi')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/api/salles',
            controller: SalleController::class,
            name: 'create_salle',
        )
    ]
)]
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

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'salle')]
    private Collection $seances;

    /**
     * @var Collection<int, Siege>
     */
    #[ORM\OneToMany(targetEntity: Siege::class, mappedBy: 'salle')]
    private Collection $sieges;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->sieges = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setSalle($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getSalle() === $this) {
                $seance->setSalle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Siege>
     */
    public function getSieges(): Collection
    {
        return $this->sieges;
    }

    public function addSiege(Siege $siege): static
    {
        if (!$this->sieges->contains($siege)) {
            $this->sieges->add($siege);
            $siege->setSalle($this);
        }

        return $this;
    }

    public function removeSiege(Siege $siege): static
    {
        if ($this->sieges->removeElement($siege)) {
            // set the owning side to null (unless already changed)
            if ($siege->getSalle() === $this) {
                $siege->setSalle(null);
            }
        }

        return $this;
    }
}
