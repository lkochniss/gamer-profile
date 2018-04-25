<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GameControllerTest extends WebTestCase
{
    /**
     * @param string $url
     * @dataProvider frontendUrlProvider
     */
    public function testFrontendGameActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function frontendUrlProvider(): array
    {
        return [
            ['/game'],
            ['/game-1'],
            ['/game-2'],
            ['/game-3'],
        ];
    }

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
            ['/admin/game'],
            ['/admin/game/1/edit'],
            ['/admin/game/1/dashboard'],
        ];
    }

    public function testGameUpdateRedirectsToDashboard(): void
    {
        $client = static::createClient();
        LoginHelper::logIn($client);
        $client->request('GET', 'admin/game/1/update');
        $crawler = $client->followRedirect();

        $this->assertContains('/admin/game/1/dashboard', $crawler->getUri());
    }

    public function testGameUpdateWithoutCredentialsRedirectsToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', 'admin/game/1/update');
        $crawler = $client->followRedirect();

        $this->assertContains('/admin/login', $crawler->getUri());
    }
}
