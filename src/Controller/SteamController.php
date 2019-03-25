<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Hybridauth\Exception\Exception;
use Hybridauth\Provider\OpenID;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class SteamController
 */
class SteamController extends Controller
{
    const ALLOWED_BETA_USERS = [
        '76561198045607524',
        '76561198049001294',
        '76561198046898250',
        '76561198036722743',
        '76561198028339624',
        '76561198012217484'
    ];

    public function authenticate(Request $request, UserRepository $userRepository)
    {
        $config = [
            'callback' => $request->getUri(),
            'openid_identifier' => 'http://steamcommunity.com/openid'
        ];

        try {
            $adapter = new OpenID($config);

            $adapter->authenticate();

            $userProfile = $adapter->getUserProfile();
            $adapter->disconnect();

            $prefix = 'https://steamcommunity.com/openid/id/';
            $steamUserId = intval(substr($userProfile->identifier, strlen($prefix)));

            if (in_array($steamUserId, $this::ALLOWED_BETA_USERS) == false) {
                return $this->redirectToRoute('login');
            }

            $user = $userRepository->findOneBy(['steamId' => $steamUserId]);
            if (is_null($user)) {
                $user = new User($steamUserId);
                $userRepository->save($user);
            }

            $firewallContext = 'admin';
            $token = new UsernamePasswordToken($user, null, $firewallContext, $user->getRoles());
            $this->get('session')->set('_security_' . $firewallContext, serialize($token));
            $this->get('security.token_storage')->setToken($token);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $this->redirectToRoute('homepage_dashboard');
    }
}
