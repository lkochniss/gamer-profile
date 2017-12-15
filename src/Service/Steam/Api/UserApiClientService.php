<?php

namespace App\Service\Steam\Api;

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
        return $this->getBasePath() . $endpoint . '?key=01014110429080B00A5F28902BD3AF09&steamid=76561198045607524&format=json';
    }
}
