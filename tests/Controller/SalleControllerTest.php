<?php

namespace App\Tests\Controller;

use App\Entity\Salle;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SalleControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/salle/crud/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Salle::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Salle index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'salle[nbPlace]' => 'Testing',
            'salle[numSalle]' => 'Testing',
            'salle[nbPlacePmr]' => 'Testing',
            'salle[cinema]' => 'Testing',
            'salle[qualite]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Salle();
        $fixture->setNbPlace('My Title');
        $fixture->setNumSalle('My Title');
        $fixture->setNbPlacePmr('My Title');
        $fixture->setCinema('My Title');
        $fixture->setQualite('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Salle');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Salle();
        $fixture->setNbPlace('Value');
        $fixture->setNumSalle('Value');
        $fixture->setNbPlacePmr('Value');
        $fixture->setCinema('Value');
        $fixture->setQualite('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'salle[nbPlace]' => 'Something New',
            'salle[numSalle]' => 'Something New',
            'salle[nbPlacePmr]' => 'Something New',
            'salle[cinema]' => 'Something New',
            'salle[qualite]' => 'Something New',
        ]);

        self::assertResponseRedirects('/salle/crud/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNbPlace());
        self::assertSame('Something New', $fixture[0]->getNumSalle());
        self::assertSame('Something New', $fixture[0]->getNbPlacePmr());
        self::assertSame('Something New', $fixture[0]->getCinema());
        self::assertSame('Something New', $fixture[0]->getQualite());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Salle();
        $fixture->setNbPlace('Value');
        $fixture->setNumSalle('Value');
        $fixture->setNbPlacePmr('Value');
        $fixture->setCinema('Value');
        $fixture->setQualite('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/salle/crud/');
        self::assertSame(0, $this->repository->count([]));
    }
}
