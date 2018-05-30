<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchaseControllerTest extends WebTestCase
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
    public function testBackendPurchaseActionsReturnOk(string $url): void
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
    public function testBackendPurchaseActionsWithoutCredentialsRedirectsToLogin(string $url): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
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
            ['/admin/purchase'],
            ['admin/game/1/purchase/create'],
            ['admin/game/1/purchase'],
            ['admin/game/2/purchase'],
            ['admin/game/3/purchase'],
            ['admin/purchase/create'],
            ['admin/purchase/1/edit'],
        ];
    }
}
