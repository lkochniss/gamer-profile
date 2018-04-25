<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class SecurityControllerTest extends WebTestCase
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

    public function testLoginActionReturnHttpOk(): void
    {
        $this->client->request('GET', '/admin/login');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
