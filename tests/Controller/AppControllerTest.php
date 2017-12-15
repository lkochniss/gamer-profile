<?php

namespace tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

class AppControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndexActionIsAccessable(): void
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
