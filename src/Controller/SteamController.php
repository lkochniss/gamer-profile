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

            $user = $userRepository->findOneBy(['steamId' => $steamUserId]);
            if (is_null($user)) {
                $user = new User($steamUserId);
                $userRepository->save($user);
            }

            $providerKey = 'admin';
            $token = new UsernamePasswordToken($user, null, $providerKey, ['ROLE_ADMIN']);
            $this->get('session')->set('_security_' . $providerKey, serialize($token));
            $this->get('security.token_storage')->setToken($token);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $this->redirectToRoute('homepage_backend_dashboard');
    }
}
