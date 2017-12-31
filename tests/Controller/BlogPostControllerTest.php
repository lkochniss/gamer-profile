<?php

namespace tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

class BlogPostControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testCreateIsAccessable(): void
    {
        $this->client->request('GET', '/blog/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
