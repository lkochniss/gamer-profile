<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginHelper
{
    const USER_1 = "76561198045607524";
    const USER_2 = "12345";

    /**
     * @param Client $client
     * @param int $userSteamId
     */
    public function logIn(Client &$client, int $userSteamId):void
    {
        $session = $client->getContainer()->get('session');
        $user = new User();
        $user->setEmail('exampleMail');
        $user->setSteamId($userSteamId);

        $firewallContext = 'admin';
        $token = new UsernamePasswordToken($user, null, $firewallContext, ['ROLE_USER']);
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
