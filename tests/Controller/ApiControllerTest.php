<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiControllerTest extends WebTestCase
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
     * @dataProvider backendUrlProvider
     */
    public function testActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        $this->loginHelper->logIn($client, LoginHelper::USER_1);
        $client->request('GET', $url);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testActionsWithoutCredentialsRedirectsToLogin(string $url): void
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
    public function backendUrlProvider(): array
    {
        return [
            ['/api/sessions/this-year'],
            ['/api/sessions/per-month'],
            ['/api/average/per-month'],
            ['/api/sessions/recently'],
            ['/api/sessions/game/1'],
            ['/api/sessions/game/1/per-month'],
            ['/api/sessions/2019'],
        ];
    }
}
