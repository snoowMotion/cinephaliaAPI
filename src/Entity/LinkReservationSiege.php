<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LinkReservationSiegeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkReservationSiegeRepository::class)]
#[ORM\Table(name: 'link_reservation_siege', schema: 'cinephaliaapi')]
#[ApiResource]
class LinkReservationSiege
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'linkReservationSieges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $reservation = null;

    #[ORM\ManyToOne(inversedBy: 'linkReservationSieges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Siege $siege = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getSiege(): ?Siege
    {
        return $this->siege;
    }

    public function setSiege(?Siege $siege): static
    {
        $this->siege = $siege;

        return $this;
    }
}
