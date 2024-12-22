<?php

namespace App\Tests\Repository;

use App\Entity\Cinema;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Qualite;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Repository\SeanceRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe SeanceRepositoryTest
 *
 * Cette classe contient des tests unitaires pour le SeanceRepository.
 */
#[CoversClass(SeanceRepository::class)]
class SeanceRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private SeanceRepository $repository;

    /**
     * Configure l'environnement de test.
     *
     * Cette méthode est appelée avant chaque test. Elle initialise le kernel de Symfony,
     * configure l'entity manager et recrée le schéma de la base de données.
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $connection = $this->entityManager->getConnection();

        // Supprime les schémas existants et crée un nouveau schéma public
        $connection->executeStatement('DROP SCHEMA IF EXISTS cinephaliaapi CASCADE');
        $connection->executeStatement('DROP SCHEMA IF EXISTS public CASCADE');
        $connection->executeStatement('CREATE SCHEMA public');

        // Récupère les métadonnées des entités et recrée le schéma
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);

        $this->repository = $this->entityManager->getRepository(Seance::class);
    }

    /**
     * Nettoie l'environnement de test.
     *
     * Cette méthode est appelée après chaque test. Elle ferme l'entity manager.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    /**
     * Teste si la méthode isSeanceExists retourne true lorsqu'une séance existe.
     *
     * Ce test crée et persiste une entité Seance, puis vérifie si la méthode
     * isSeanceExists identifie correctement la séance existante.
     */

    public function testIsSeanceExistsReturnsTrue(): void
    {
        // Crée et persiste une entité Seance
        $seance = new Seance();
        $film = new Film();
        $genre = new Genre();
        $salle = new Salle();
        $cinema = new Cinema();
        $qualite = new Qualite();

        // Définit les propriétés des entités
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

        $genre->setLibelle('Action');
        $film->setGenre($genre);
        $film->setTitre('Film 1');
        $film->setSynopsis('Synopsis 1');
        $film->setAfficheUrl('https://www.google.com');
        $film->setAgeMini(12);
        $film->setLabel('label');
        $this->entityManager->persist($genre);
        $this->entityManager->persist($film);

        $seance->setFilm($film);
        $seance->setDateDebut(new \DateTime('2024-12-21 09:00:00'));
        $seance->setDateFin(new \DateTime('2024-12-21 11:00:00'));
        $seance->setSalle($salle);
        $this->entityManager->persist($seance);
        $this->entityManager->flush();

        // Teste si la séance existe dans l'intervalle de temps donné
        $result = $this->repository->isSeanceExists(1, new \DateTime('2024-12-21 10:00:00'), new \DateTime('2024-12-21 12:00:00'));
        $this->assertTrue($result);

        // Teste si la séance n'existe pas en dehors de l'intervalle de temps donné
        $result = $this->repository->isSeanceExists(1, new \DateTime('2024-12-21 08:00:00'), new \DateTime('2024-12-21 09:00:00'));
        $this->assertFalse($result);
    }

    /**
     * Teste si la méthode isSeanceExists retourne false lorsqu'aucune séance n'existe.
     *
     * Ce test vérifie si la méthode isSeanceExists identifie correctement
     * qu'aucune séance n'existe lorsqu'aucune n'est persistée.
     */
    public function testIsSeanceExistsReturnsFalse(): void
    {
        // Aucune séance n'est persistée

        // Teste si la séance n'existe pas dans l'intervalle de temps donné
        $result = $this->repository->isSeanceExists(1, new \DateTime('2024-12-21 10:00:00'), new \DateTime('2024-12-21 12:00:00'));
        $this->assertFalse($result);
    }
}
