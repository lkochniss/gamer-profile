<?php


namespace App\Tests\Controller;

use App\Tests\DataPrimer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class HomePageControllerTest extends WebTestCase
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

    public function testRecentlyPlayedPageHasStatus200(): void
    {
        $this->client->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
