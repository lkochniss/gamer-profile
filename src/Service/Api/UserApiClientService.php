<?php

namespace App\Service\Api;

/**
 * Class UserApiClientService
 */
class UserApiClientService extends AbstractApiClientService
{
    protected function getBasePath() : string
    {
        return 'http://api.steampowered.com';
    }

    /**
     * @param string $endpoint
     *
     * @return string
     */
    protected function generateRequestUrl($endpoint) : string
    {
        $separator = '?';

        if (strpos($endpoint, $separator) !== false) {
            $separator = '&';
        }

        return $this->getBasePath() . $endpoint . $separator .
            'key='. getenv('STEAM_API_KEY') .'&steamid='. getenv('STEAM_USER_ID').'&format=json';
    }
}
