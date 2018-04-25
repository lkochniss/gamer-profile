<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PurchaseControllerTest extends WebTestCase
{
    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendPurchaseActionsReturnOk(string $url): void
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
    public function testBackendPurchaseActionsWithoutCredentialsRedirectsToLogin(string $url): void
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
