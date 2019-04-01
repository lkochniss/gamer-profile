<?php

namespace App\Controller;

use App\Service\Security\UserProvider;
use Hybridauth\Exception\Exception;
use Hybridauth\Provider\OpenID;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SteamController
 */
class SteamController extends Controller
{

    /**
     * @param Request $request
     * @param UserProvider $userProvider
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connect(Request $request, UserProvider $userProvider, UserInterface $user)
    {
        $config = [
            'callback' => $request->getUri() ,
            'openid_identifier' => 'http://steamcommunity.com/openid'
        ];

        try {
            $adapter = new OpenID($config);

            $adapter->authenticate();

            $userProfile = $adapter->getUserProfile();
            $adapter->disconnect();

            $prefix = 'https://steamcommunity.com/openid/id/';
            $steamUserId = intval(substr($userProfile->identifier, strlen($prefix)));
            $userProvider->saveSteamUserId($user->getUsername(), $steamUserId);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $this->redirectToRoute('homepage_dashboard');
    }
}
