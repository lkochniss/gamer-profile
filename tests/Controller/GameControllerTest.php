<?php


namespace App\Tests\Controller;

use App\Tests\DataPrimer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class GameControllerTest extends WebTestCase
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

    public function testHomepageControllerActionsReturnHttpOk(): void
    {
        $this->client->request('GET', '/game');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
