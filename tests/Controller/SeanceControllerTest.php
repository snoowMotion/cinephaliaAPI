<?php

namespace App\Tests\Controller;

use App\Controller\SeanceController;
use App\Entity\LinkReservationSiege;
use App\Repository\SeanceRepository;
use App\Tests\InitDataWebTest;
use PHPUnit\Framework\Attributes\CoversClass;

use Symfony\Component\HttpFoundation\Response;

#[CoversClass(SeanceController::class)]
class SeanceControllerTest extends InitDataWebTest
{

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
