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
     * @dataProvider urlProvider
     */
    public function testPurchaseActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        $this->loginHelper->logIn($client);
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider urlProvider
     */
    public function testPurchaseActionsWithoutCredentialsRedirectsToLogin(string $url): void
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
            ['/purchase'],
            ['/game/1/purchase/create'],
            ['/game/1/purchase'],
            ['/game/2/purchase'],
            ['/game/3/purchase'],
            ['/purchase/create'],
            ['/purchase/1/edit'],
        ];
    }
}
