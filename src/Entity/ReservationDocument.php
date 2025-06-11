<?php

namespace App\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'reservations')]
class ReservationDocument
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $userId;

    #[MongoDB\Field(type: 'string')]
    private string $filmId;

    #[MongoDB\Field(type: 'string')]
    private string $seanceId;

    #[MongoDB\Field(type: 'string')]
    private string $cinema;

    #[MongoDB\Field(type: 'collection')]
    private array $places = [];

    #[MongoDB\Field(type: 'string')]
    private string $qualite;

    #[MongoDB\Field(type: 'float')]
    private float $prixTotal;

    #[MongoDB\Field(type: 'date')]
    private \DateTime $dateReservation;

    // Getters/Setters

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getFilmId(): string
    {
        return $this->filmId;
    }

    public function setFilmId(string $filmId): self
    {
        $this->filmId = $filmId;
        return $this;
    }

    public function getSeanceId(): string
    {
        return $this->seanceId;
    }

    public function setSeanceId(string $seanceId): self
    {
        $this->seanceId = $seanceId;
        return $this;
    }

    public function getCinema(): string
    {
        return $this->cinema;
    }

    public function setCinema(string $cinema): self
    {
        $this->cinema = $cinema;
        return $this;
    }

    public function getPlaces(): array
    {
        return $this->places;
    }

    public function setPlaces(array $places): self
    {
        $this->places = $places;
        return $this;
    }

    public function getQualite(): string
    {
        return $this->qualite;
    }

    public function setQualite(string $qualite): self
    {
        $this->qualite = $qualite;
        return $this;
    }

    public function getPrixTotal(): float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;
        return $this;
    }

    public function getDateReservation(): \DateTime
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTime $dateReservation): self
    {
        $this->dateReservation = $dateReservation;
        return $this;
    }
}
