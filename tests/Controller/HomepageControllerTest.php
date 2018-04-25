<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class HomepageControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @param string $url
     * @dataProvider urlProvider
     */
    public function testActionsReturnHttpOk(string $url): void
    {
        $this->client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function urlProvider(): array
    {
        return [
            ['/'],
            ['/top'],
            ['/new'],
            ['/game-of-the-month'],
        ];
    }
}
