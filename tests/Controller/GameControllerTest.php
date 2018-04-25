<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class GameControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGameListReturnHttpOk(): void
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
}
