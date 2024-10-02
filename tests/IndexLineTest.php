<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\LineRepository;
use App\Entity\Line;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class IndexLineTest extends WebTestCase
{

    private ?EntityManagerInterface $entityManager;
    private ?LineRepository $lineRepository;
    private ?KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->lineRepository = $this->entityManager->getRepository(Line::class);
    }


    // smoke test
    public function testSmokeTest(): void
    {

        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    // test lines
    public function testGetLines(): void
    {
        $lines = $this->lineRepository->findAll();

        $this->assertIsArray($lines);
    }

    // test getting the train from the line
    public function testGetTrain(): void
    {

        $lines = $this->lineRepository->findAll();

        foreach ($lines as $line) {
            $train = $line->getTrain();
            $this->assertNotNull($train);
        }
    }

    // test getting the stops from the line
    public function testGetStops(): void
    {
        $lines = $this->lineRepository->findAll();

        foreach ($lines as $line) {
            $stops = $line->getStops();
            $this->assertIsArray($stops->toArray());
        }
    }

    // test getting the stops in order from the line
    public function testGetStopsInOrder(): void
    {
        $lines = $this->lineRepository->findAll();

        foreach ($lines as $line) {
            $stops = $line->getStopsInOrder();
            $this->assertIsArray($stops);
        }
    }

    // test getting the delay from the line
    public function testGetDelay(): void
    {
        $lines = $this->lineRepository->findAll();

        foreach ($lines as $line) {
            $delay = $line->getDelay();
            $this->assertIsInt($delay);
        }
    }
}
