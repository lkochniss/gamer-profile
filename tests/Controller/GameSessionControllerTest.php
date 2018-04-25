<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class GameSessionControllerTest extends WebTestCase
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

    public function testSessionListOk(): void
    {
        $this->client->request('GET', '/admin/session');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider gameSessionUrlProvider
     */
    public function testSessionListForGame(string $url): void
    {
        $this->logIn();
        $this->client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function gameSessionUrlProvider(): array
    {
        return [
            ['admin/game/1/session'],
            ['admin/game/2/session'],
            ['admin/game/3/session'],
        ];
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'secured_area';

        $token = new UsernamePasswordToken('admin', null, $firewallContext, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
