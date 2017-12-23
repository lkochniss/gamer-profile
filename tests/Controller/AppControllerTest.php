<?php

namespace tests\App\Controller;

use App\Controller\AppController;
use App\Repository\GameRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

class AppControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var MockObject
     */
    private $gameRepositoryMock;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->gameRepositoryMock = $this->createMock(GameRepository::class);
    }

    public function testIndexActionIsAccessable(): void
    {
        $this->setGameRepositoryMock();
        $this->client->getContainer()->set('App\Repository\GameRepository', $this->gameRepositoryMock);

        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    private function setGameRepositoryMock():void
    {
        $this->gameRepositoryMock->expects($this->any())
            ->method('getRecentlyPlayedGames')
            ->willReturn([]);

        $this->gameRepositoryMock->expects($this->any())
            ->method('getMostPlayedGames')
            ->willReturn([]);
    }
}
