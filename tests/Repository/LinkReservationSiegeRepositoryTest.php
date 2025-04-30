<?php

namespace App\Tests\Repository;

use App\Entity\LinkReservationSiege;
use App\Repository\LinkReservationSiegeRepository;
use App\Tests\InitDataTest;


class LinkReservationSiegeRepositoryTest extends InitDataTest
{

    public function testGetFirstPlace()
    {
        $linkReservationSiegeRepository = $this->entityManager->getRepository(LinkReservationSiege::class);
        $seance = $this->createData();

        $firstAvailableSeat = $linkReservationSiegeRepository->getFirstPlace($seance->getId(), false);

        $this->assertNotNull($firstAvailableSeat);
        $this->assertFalse($firstAvailableSeat->getSiege()->isPMR());

        $firstAvailablePmrSeat = $linkReservationSiegeRepository->getFirstPlace($seance->getId(), true);
        $this->assertNotNull($firstAvailablePmrSeat);
        $this->assertTrue($firstAvailablePmrSeat->getSiege()->isPMR());
    }

    public function testGetNbPlaceDisponible(): void
    {
        $seance = $this->createData();
        $linkReservationSiegeRepository = $this->entityManager->getRepository(LinkReservationSiege::class);
        $nbPlaceDisponible = $linkReservationSiegeRepository->getNbPlaceDisponible($seance->getId(), false);
        $this->assertEquals(100, $nbPlaceDisponible);

        $nbPlacePmrDisponible = $linkReservationSiegeRepository->getNbPlaceDisponible($seance->getId(), true);
        $this->assertEquals(10, $nbPlacePmrDisponible);
    }
}
