<?php

namespace App\Controller;

use App\Service\Security\UserProvider;
use Hybridauth\Exception\Exception;
use Hybridauth\Provider\OpenID;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SteamController
 */
class SteamController extends AbstractController
{

    /**
     * @param string $username
     * @param Request $request
     * @param UserProvider $userProvider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connect(string $username, Request $request, UserProvider $userProvider)
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
            $userProvider->saveSteamUserId($username, $steamUserId);
        } catch (Exception $exception) {
        }

        return $this->redirectToRoute('homepage_dashboard');
    }
}
