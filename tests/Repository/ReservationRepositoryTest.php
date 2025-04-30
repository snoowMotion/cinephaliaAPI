<?php

namespace App\Tests\Repository;

use App\Entity\LinkReservationSiege;
use App\Entity\Reservation;
use App\Entity\Siege;
use App\Entity\User;

use App\Tests\InitDataTest;


class ReservationRepositoryTest extends InitDataTest
{


    public function testGetNbSiegeReserve(): void
    {
        $seance = $this->createData();

        $user = new User();
        $user->setEmail('test@test.fr');
        $user->setPassword('test');
        $user->setNom('test');
        $user->setPrenom('test');
        $this->entityManager->persist($user);

        $reservation = new Reservation();
        $reservation->setClient($user);
        $reservation->setSeance($seance);
        $reservation->setFinish(false);
        $this->entityManager->persist($reservation);

        for ($i = 0; $i < 5; $i++) {
            $siege = new Siege();
            $siege->setNumero($i);
            $siege->setSalle($seance->getSalle());
            $this->entityManager->persist($siege);

            $linkReservationSiege = new LinkReservationSiege();
            $linkReservationSiege->setReservation($reservation);
            $linkReservationSiege->setSiege($siege);
            $this->entityManager->persist($linkReservationSiege);
        }

        $this->entityManager->flush();

        $nbSiegeReserve = $this->reservationRepository->getNbSiegeReserve($seance->getId());

        $this->assertEquals(5, $nbSiegeReserve);
    }



}