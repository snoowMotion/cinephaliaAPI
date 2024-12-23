<?php

namespace App\Tests\Controller;

use App\Controller\SeanceController;
use App\Entity\Cinema;
use App\Entity\Genre;
use App\Entity\LinkReservationSiege;
use App\Entity\Qualite;
use App\Entity\Role;
use App\Entity\Seance;
use App\Entity\Siege;
use App\Entity\User;
use App\Repository\SeanceRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Film;
use App\Entity\Salle;

#[CoversClass(SeanceController::class)]
class SeanceControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private SeanceRepository $repository;
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
        for ($i = 0; $i < 100; $i++) {
            $siege = new Siege();
            $siege->setNumero($i);
            $siege->setSalle($salle);
            $this->entityManager->persist($siege);
        }
        $genre->setLibelle('Action');
        $film->setGenre($genre);
        $film->setTitre('Film 1');
        $film->setSynopsis('Synopsis 1');
        $film->setAfficheUrl('https://www.google.com');
        $film->setAgeMini(12);
        $film->setLabel('label');
        if ($withSeance) {
            $seance->setFilm($film);
            $seance->setSalle($salle);
            $seance->setDateDebut(new \DateTime('2024-12-21 09:00:00'));
            $seance->setDateFin(new \DateTime('2024-12-21 11:00:00'));
            $this->entityManager->persist($seance);
        }
        $this->entityManager->persist($genre);
        $this->entityManager->persist($film);

        $this->entityManager->flush();
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
        // Simule la connexion de $testUser
        $this->client->loginUser($testUser);
    }

    public function testCreateSeanceSuccess(): void
    {
        $this->createData();
        $this->logIn();
        // Prépare les données de la requête
        $data = [
            'film' => 1,
            'salle' => 1,
            'dateDebut' => '2024-12-21 09:00:00',
            'dateFin' => '2024-12-21 11:00:00'
        ];

        // Envoie la requête
        $this->client->request(
            'POST',
            '/api/seances',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        dump($this->client->getResponse()->getContent());
        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'Séance crée!']), $this->client->getResponse()->getContent());
    }

    public function testCreateSeanceMissingParameters(): void
    {
        $this->createData();
        $this->logIn();
        // Prépare les données de la requête avec des paramètres manquants
        $data = [
            'film' => 1,
            'salle' => 1,
            'dateDebut' => '2024-12-21 09:00:00'
        ];

        // Envoie la requête
        $this->client->request(
            'POST',
            '/api/seances',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'Paramètre absent']), $this->client->getResponse()->getContent());
    }

    public function testCreateSeanceFilmNotFound(): void
    {
        $this->createData();
        $this->logIn();
        // Prépare les données de la requête avec un film non existant
        $data = [
            'film' => 999,
            'salle' => 1,
            'dateDebut' => '2024-12-21 09:00:00',
            'dateFin' => '2024-12-21 11:00:00'
        ];

        // Envoie la requête
        $this->client->request('POST', '/api/seances', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'Film introuvable']), $this->client->getResponse()->getContent());
    }

    public function testCreateSeanceSalleNotFound(): void
    {
        $this->createData();
        $this->logIn();
        // Prépare les données de la requête avec une salle non existante
        $data = [
            'film' => 1,
            'salle' => 999,
            'dateDebut' => '2024-12-21 09:00:00',
            'dateFin' => '2024-12-21 11:00:00'
        ];

        // Envoie la requête
        $this->client->request('POST', '/api/seances', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'Salle introuvable']), $this->client->getResponse()->getContent());
    }

    public function testCreateSeanceInvalidDateFormat(): void
    {
        $this->createData();
        $this->logIn();
        // Prépare les données de la requête avec un format de date invalide
        $data = [
            'film' => 1,
            'salle' => 1,
            'dateDebut' => 'invalid-date',
            'dateFin' => '2024-12-21 11:00:00'
        ];

        // Envoie la requête
        $this->client->request('POST', '/api/seances', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'Format de date invalide']), $this->client->getResponse()->getContent());
    }

    public function testCreateSeanceDateDebutAfterDateFin(): void
    {
        $this->createData();
        $this->logIn();
        // Prépare les données de la requête avec dateDebut après dateFin
        $data = [
            'film' => 1,
            'salle' => 1,
            'dateDebut' => '2024-12-21 12:00:00',
            'dateFin' => '2024-12-21 11:00:00'
        ];

        // Envoie la requête
        $this->client->request('POST', '/api/seances', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'Date de début après la date de fin']), $this->client->getResponse()->getContent());
    }

    public function testCreateSeanceAlreadyExists(): void
    {
        $this->createData(true);
        $this->logIn();
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        $entityManager->flush();

        // Prépare les données de la requête
        $data = [
            'film' => 1,
            'salle' => 1,
            'dateDebut' => '2024-12-21 09:00:00',
            'dateFin' => '2024-12-21 11:00:00'
        ];

        // Envoie la requête
        $this->client->request('POST', '/api/seances', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['status' => 'Séance déjà existante']), $this->client->getResponse()->getContent());
    }

    public function testLinkReservationSiegeCountMatchesSalleSeats(): void
    {
        $this->createData();
        $this->logIn();

        // Prépare les données de la requête
        $data = [
            'film' => 1,
            'salle' => 1,
            'dateDebut' => '2024-12-21 09:00:00',
            'dateFin' => '2024-12-21 11:00:00'
        ];

        // Envoie la requête
        $this->client->request(
            'POST',
            '/api/seances',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        // Vérifie la réponse
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // Récupère la séance créée
        $seance = $this->repository->findOneBy(['film' => 1, 'salle' => 1, 'dateDebut' => new \DateTime('2024-12-21 09:00:00')]);

        // Récupère le nombre de liens LinkReservationSiege pour la séance
        $linkReservationSiegeCount = $this->entityManager->getRepository(LinkReservationSiege::class)
            ->count();

        // Récupère le nombre de places dans la salle
        $salle = $seance->getSalle();
        $nbPlace = $salle->getNbPlace() + $salle->getNbPlacePmr();

        // Vérifie que le nombre de liens LinkReservationSiege correspond au nombre de places dans la salle
        $this->assertEquals($nbPlace, $linkReservationSiegeCount);
    }
}
