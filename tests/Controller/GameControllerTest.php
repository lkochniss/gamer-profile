<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GameControllerTest extends WebTestCase
{
    public function testGameListReturnHttpOk(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider gamesProvider
     */
    public function testDifferentGames(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function gamesProvider(): array
    {
        return [
            ['/game-1'],
            ['/game-2'],
            ['/game-3'],
        ];
    }
}
