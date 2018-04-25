<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GameSessionControllerTest extends WebTestCase
{

    public function testSessionListReturnsOk(): void
    {
        $client = static::createClient();
        LoginHelper::logIn($client);
        $client->request('GET', '/admin/session');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testSessionListWithoutCredentialsRedirect(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/session');

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider gameSessionUrlProvider
     */
    public function testSessionListForGameReturnsOk(string $url): void
    {
        $client = static::createClient();
        LoginHelper::logIn($client);
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider gameSessionUrlProvider
     */
    public function testSessionListForGameWithoutCredentialsRedirect(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function gameSessionUrlProvider(): array
    {
        return [
            ['admin/game/1/session'],
            ['admin/game/2/session'],
            ['admin/game/3/session'],
        ];
    }
}
