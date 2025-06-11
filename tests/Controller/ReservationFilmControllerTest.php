<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ReservationFilmControllerTest extends WebTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
    }
    public function testGetFilmByCinema(): void
    {

        // Récupérer le repository User
        $userRepository = self::getContainer()->get(UserRepository::class);
        // Récupérer le user depuis la base
        $user = $userRepository ->findAll();
        $this->assertInstanceOf(UserInterface::class, $user);

        // Simuler la connexion
        $this ->client->loginUser($user);


        // ID d'un cinéma existant avec des films à venir (doit exister via fixtures)
        $cinemaId = 1;

        $client->request('GET', '/reservation/getFilmByCinema', [
            'cinemaId' => $cinemaId
        ]);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);

        foreach ($data as $film) {
            $this->assertArrayHasKey('id', $film);
            $this->assertArrayHasKey('titre', $film);
        }
    }

    public function testGetFilmByCinemaWithInvalidId(): void
    {
        $client = static::createClient();

        $client->request('POST', '/login', [
            'email' => 'user@example.com',
            'password' => 'Password123!'
        ]);

        $this->assertResponseIsSuccessful();

        // ID inexistant
        $client->request('GET', '/reservation/getFilmByCinema', [
            'cinemaId' => 9999
        ]);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Cinema not found', $data['message']);
    }
}
