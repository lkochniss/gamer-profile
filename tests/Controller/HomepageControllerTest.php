<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageControllerTest extends WebTestCase
{

    /**
     * @param string $url
     * @dataProvider urlProvider
     */
    public function testActionsReturnHttpOk(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
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
