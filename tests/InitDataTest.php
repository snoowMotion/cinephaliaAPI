<?php

namespace App\Tests;

use App\Entity\Cinema;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\LinkReservationSiege;
use App\Entity\Qualite;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Entity\Siege;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InitDataTest extends KernelTestCase
{
    public EntityManagerInterface $entityManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $connection = $this->entityManager->getConnection();

        $connection->executeStatement('DROP SCHEMA IF EXISTS cinephaliaapi CASCADE');
        $connection->executeStatement('DROP SCHEMA IF EXISTS public CASCADE');
        $connection->executeStatement('CREATE SCHEMA public');

        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);

        $this->reservationRepository = $this->entityManager->getRepository(Reservation::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function createData()
    {
        $seance = new Seance();
        $film = new Film();
        $genre = new Genre();
        $salle = new Salle();
        $cinema = new Cinema();
        $qualite = new Qualite();

        $cinema->setVille('Paris');
        $this->entityManager->persist($cinema);

        $qualite->setLibelle('qualite');
        $qualite->setPrix(10);
        $this->entityManager->persist($qualite);

        $salle->setCinema($cinema);
        $salle->setQualite($qualite);
        $salle->setNbPlace(100);
        $salle->setNumSalle('1');
        $salle->setNbPlacePmr(10);
        $this->entityManager->persist($salle);
        for ($i = 0; $i < 100; $i++) {
            $siege = new Siege();
            $siege->setNumero($i);
            $siege->setSalle($salle);
            $siege->setPMR(false);
            $this->entityManager->persist($siege);
            $this->entityManager->flush();
        }
        for($i = 0; $i < 10; $i++) {
            $siege = new Siege();
            $siege->setNumero($i);
            $siege->setSalle($salle);
            $siege->setPMR(true);
            $this->entityManager->persist($siege);
            $this->entityManager->flush();
        }
        $genre->setLibelle('Action');
        $film->setGenre($genre);
        $film->setTitre('Film 1');
        $film->setSynopsis('Synopsis 1');
        $film->setAfficheUrl('https://www.google.com');
        $film->setAgeMini(12);
        $film->setLabel('label');
        $seance->setFilm($film);
        $seance->setSalle($salle);
        $seance->setDateDebut(new \DateTime('2024-12-21 09:00:00'));
        $seance->setDateFin(new \DateTime('2024-12-21 11:00:00'));
        $this->entityManager->persist($seance);
        $sieges = $this->entityManager->getRepository(Siege::class)->findBy(['salle' => $salle, 'isPMR' => false]);
        $siegesPmr = $this->entityManager->getRepository(Siege::class)->findBy(['salle' => $salle, 'isPMR' => true]);
        foreach ($sieges as $siege) {
            $linkSeanceSiege = new LinkReservationSiege();
            $linkSeanceSiege->setSiege($siege);
            $linkSeanceSiege->setSeance($seance);
            $this->entityManager->persist($linkSeanceSiege);
        }
        foreach ($siegesPmr as $siege) {
            $linkSeanceSiege = new LinkReservationSiege();
            $linkSeanceSiege->setSiege($siege);
            $linkSeanceSiege->setSeance($seance);
            $this->entityManager->persist($linkSeanceSiege);
        }
        $this->entityManager->persist($genre);
        $this->entityManager->persist($film);

        $this->entityManager->flush();

        return $seance;
    }


}