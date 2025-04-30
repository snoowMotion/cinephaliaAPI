<?php

namespace App\Tests\Controller;

use App\Tests\InitDataWebTest;

use Symfony\Component\HttpFoundation\Response;

class ReservationControllerTest extends InitDataWebTest
{

    public function testCreateReservationSuccess(): void
    {
        $this->createData(true);
        $this->logIn();

        $data = [
            'seance' => 1,
            'nbSiege' => 5,
            'nbSiegePmr' => 2
        ];

        $this->client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        dump($this->client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['message' => 'Reservation crée avec succès']), $this->client->getResponse()->getContent());
    }

    public function testCreateReservationMissingParameters(): void
    {
        $this->createData(true);
        $this->logIn();

        $data = [
            'seance' => 1,
            'nbSiege' => 5
        ];

        $this->client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'Paramètre absent']), $this->client->getResponse()->getContent());
    }

    public function testCreateReservationSeanceNotFound(): void
    {
        $this->createData();
        $this->logIn();

        $data = [
            'seance' => 999,
            'nbSiege' => 5,
            'nbSiegePmr' => 2
        ];

        $this->client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'Seance introuvable']), $this->client->getResponse()->getContent());
    }

    public function testCreateReservationExceedsAvailableSeats(): void
    {
        $this->createData(true);
        $this->logIn();

        $data = [
            'seance' => 1,
            'nbSiege' => 200,
            'nbSiegePmr' => 2
        ];

        $this->client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'Nombre de place reservées dépasse le nombre de place disponible']), $this->client->getResponse()->getContent());
    }

    public function testCreateReservationExceedsAvailablePmrSeats(): void
    {
        $this->createData(true);
        $this->logIn();

        $data = [
            'seance' => 1,
            'nbSiege' => 5,
            'nbSiegePmr' => 20
        ];

        $this->client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'Nombre de place PMR reservées dépasse le nombre de place PMR disponible']), $this->client->getResponse()->getContent());
    }
}