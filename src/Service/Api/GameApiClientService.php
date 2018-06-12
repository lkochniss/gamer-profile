<?php

namespace App\Service\Api;

/**
 * Class GameApiClientService
 */
class GameApiClientService extends AbstractApiClientService
{
    protected function getBasePath() : string
    {
        return 'https://store.steampowered.com';
    }

    /**
     * @param string $endpoint
     *
     * @return string
     */
    protected function generateRequestUrl($endpoint) : string
    {
        return $this->getBasePath() . $endpoint;
    }
}
