<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GameControllerTest extends WebTestCase
{
    /**
     * @var LoginHelper
     */
    private $loginHelper;

    public function setUp()
    {
        $this->loginHelper = new LoginHelper();
    }

    /**
     * @param string $url
     * @dataProvider urlProvider
     */
    public function testGameActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        $this->loginHelper->logIn($client, LoginHelper::USER_1);
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider urlProvider
     */
    public function testBackendGameActionsWithoutCredentialsRedirectsToLogin(string $url): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', $url);
        $crawler = $client->followRedirect();

        $this->assertContains('/login', $crawler->getUri());
    }

    /**
     * @return array
     */
    public function urlProvider(): array
    {
        return [
            ['/game'],
            ['/game/1/dashboard'],
        ];
    }

    public function testGameUpdateRedirectsToDashboard(): void
    {
        $client = static::createClient();
        $this->loginHelper->logIn($client);
        $client->request('GET', 'game/1/update');
        $crawler = $client->followRedirect();

        $this->assertContains('/game/1/dashboard', $crawler->getUri());
    }

    public function testGameUpdateWithoutCredentialsRedirectsToLogin(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', 'game/1/update');
        $crawler = $client->followRedirect();

        $this->assertContains('/login', $crawler->getUri());
    }
}
