<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\SeanceController;
use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
#[ORM\Table(name: 'seance', schema: 'cinephaliaapi')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/api/seances',
            controller: SeanceController::class,
            name: 'create_seance',
        )
    ],
)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salle $salle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Film $film = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'seance')]
    private Collection $reservations;

    /**
     * @var Collection<int, LinkReservationSiege>
     */
    #[ORM\OneToMany(targetEntity: LinkReservationSiege::class, mappedBy: 'seance')]
    private Collection $linkReservationSieges;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->linkReservationSieges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this->film;
    }

    public function setFilm(?Film $film): static
    {
        $this->film = $film;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setSeance($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSeance() === $this) {
                $reservation->setSeance(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LinkReservationSiege>
     */
    public function getLinkReservationSieges(): Collection
    {
        return $this->linkReservationSieges;
    }

    public function addLinkReservationSiege(LinkReservationSiege $linkReservationSiege): static
    {
        if (!$this->linkReservationSieges->contains($linkReservationSiege)) {
            $this->linkReservationSieges->add($linkReservationSiege);
            $linkReservationSiege->setSeance($this);
        }

        return $this;
    }

    public function removeLinkReservationSiege(LinkReservationSiege $linkReservationSiege): static
    {
        if ($this->linkReservationSieges->removeElement($linkReservationSiege)) {
            // set the owning side to null (unless already changed)
            if ($linkReservationSiege->getSeance() === $this) {
                $linkReservationSiege->setSeance(null);
            }
        }

        return $this;
    }
}
