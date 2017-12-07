<?php

namespace App\Service\Steam\Api;

/**
 * Class GameApiClientService
 */
class GameApiClientService extends AbstractApiClientService
{
    protected function getBasePath() : string
    {
        return 'https://store.steampowered.com/api';
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
