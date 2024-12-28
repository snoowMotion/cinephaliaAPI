<?php

namespace App\Tests;

use App\Entity\Cinema;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\LinkReservationSiege;
use App\Entity\Qualite;
use App\Entity\Role;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Entity\Siege;
use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InitDataWebTest extends WebTestCase
{
        public $client;

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
        $this->client = static::createClient();
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

    public function createData($withSeance = false)
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


    public function logIn()
    {
        $testUser = new User();
        $role = new Role();
        $role->setLibelle('ROLE_ADMIN');
        $testUser->setEmail('test@test.fr');
        $testUser->setPassword('test');
        $testUser->setNom('test');
        $testUser->setPrenom('test');
        $testUser->addRole($role);
        $this->entityManager->persist($testUser);
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        // Simule la connexion de $testUser
        $this->client->loginUser($testUser);
    }
}