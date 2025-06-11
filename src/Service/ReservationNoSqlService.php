<?php

namespace App\Service;

use App\Entity\ReservationDocument;
use Doctrine\ODM\MongoDB\DocumentManager;

class ReservationNoSqlService
{
    public function __construct(
        private readonly DocumentManager $documentManager
    ) {}

    public function enregistrerReservation(array $data): ReservationDocument
    {
        $reservation = new ReservationDocument();

        $reservation
            ->setUserId($data['userId'])
            ->setFilmId($data['filmId'])
            ->setSeanceId($data['seanceId'])
            ->setCinema($data['cinema'])
            ->setPlaces($data['places']) // ex: [['salle' => 1, 'numeroSiege' => 'A1']]
            ->setQualite($data['qualite'])
            ->setPrixTotal($data['prixTotal'])
            ->setDateReservation(new \DateTime());

        $this->documentManager->persist($reservation);
        $this->documentManager->flush();

        return $reservation;
    }
}