<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\ReservationController;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservation', schema: 'cinephaliaapi')]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/custom-endpoint',
            controller: ReservationController::class,
        )
    ]
)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Seance $seance = null;

    #[ORM\Column]
    private ?bool $isFinish = null;

    /**
     * @var Collection<int, LinkReservationSiege>
     */
    #[ORM\OneToMany(targetEntity: LinkReservationSiege::class, mappedBy: 'reservation')]
    private Collection $linkReservationSieges;

    public function __construct()
    {
        $this->linkReservationSieges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

    public function isFinish(): ?bool
    {
        return $this->isFinish;
    }

    public function setFinish(bool $isFinish): static
    {
        $this->isFinish = $isFinish;

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
            $linkReservationSiege->setReservation($this);
        }

        return $this;
    }

    public function removeLinkReservationSiege(LinkReservationSiege $linkReservationSiege): static
    {
        if ($this->linkReservationSieges->removeElement($linkReservationSiege)) {
            // set the owning side to null (unless already changed)
            if ($linkReservationSiege->getReservation() === $this) {
                $linkReservationSiege->setReservation(null);
            }
        }

        return $this;
    }
}
