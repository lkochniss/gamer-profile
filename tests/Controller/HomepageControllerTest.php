<?php


namespace App\Tests\Controller;

use App\Tests\DataPrimer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class HomepageControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        DataPrimer::setUp(self::bootKernel());
        $this->client = $client = static::createClient();
    }

    /**
     * @param string $url
     * @dataProvider urlProvider
     */
    public function testHomepageControllerActionsReturnHttpOk(string $url): void
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
        ];
    }

    /**
     * @throws \Exception
     */
    public function tearDown(): void
    {
        DataPrimer::drop(self::bootKernel());
    }
}
