<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginReturnOk(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testLogoutRedirectsToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', 'logout');
        $crawler = $client->followRedirect();
        $this->assertContains('/login', $crawler->getUri());
    }
}
