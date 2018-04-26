<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageControllerTest extends WebTestCase
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
     * @dataProvider frontendUrlProvider
     */
    public function testFrontendActionsReturnHttpOk(string $url): void
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
            ['/'],
            ['/top'],
            ['/new'],
            ['/game-of-the-month'],
        ];
    }

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendActionsReturnHttpOk(string $url): void
    {
        $client = static::createClient();
        $this->loginHelper->logIn($client);
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendActionsWithoutCredentialsRedirectsToLogin(string $url): void
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
            ['/admin/'],
        ];
    }
}
