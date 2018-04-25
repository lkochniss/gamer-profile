<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GameSessionControllerTest extends WebTestCase
{
    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendGameActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        LoginHelper::logIn($client);
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendGameActionsWithoutCredentialsRedirectsToLogin(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $crawler = $client->followRedirect();

        $this->assertContains('/admin/login', $crawler->getUri());
    }

    /**
     * @return array
     */
    public function backendUrlProvider(): array
    {
        return [
            ['/admin/session'],
            ['admin/game/1/session'],
            ['admin/game/2/session'],
            ['admin/game/3/session'],
        ];
    }
}
