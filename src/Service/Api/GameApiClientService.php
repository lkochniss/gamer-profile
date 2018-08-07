<?php

namespace App\Service\Api;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class GameApiClientService
 */
class GameApiClientService
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * Client constructor.
     *
     * @param GuzzleClient $guzzleClient
     */
    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param string $endpoint
     *
     * @return Response
     */
    public function get(string $endpoint) : Response
    {
        return $this->guzzleClient->request(
            'GET',
            $this->generateRequestUrl($endpoint),
            [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ]
            ]
        );
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function generateRequestUrl(string $endpoint): string
    {
        $separator = '?';

        if (strpos($endpoint, $separator) !== false) {
            $separator = '&';
        }

        return 'https://store.steampowered.com' . $endpoint . $separator .'l=english';
    }
}
