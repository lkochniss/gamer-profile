<?php


namespace App\Tests\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Tests\DataPrimer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class GameControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $kernel = self::bootKernel();
        DataPrimer::setUp($kernel);
        $this->client = $client = static::createClient();
    }

    public function testHomepageControllerActionsReturnHttpOk(): void
    {
        $this->client->request('GET', '/game');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider gamesProvider
     */
    public function testDifferentGames(string $url): void
    {
        $this->client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function gamesProvider(): array
    {
        return [
            ['/game-1'],
            ['/game-2'],
            ['/game-3'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function tearDown(): void
    {
        DataPrimer::drop(self::bootKernel());
    }
}
