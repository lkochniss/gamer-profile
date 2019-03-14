<?php

namespace App\Service\Api;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class UserApiClientService
 */
class UserApiClientService
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
     * @param int $steamUserId
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $endpoint, int $steamUserId) : Response
    {
        return $this->guzzleClient->request(
            'GET',
            $this->generateRequestUrl($endpoint, $steamUserId),
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
     * @param int $steamUserId
     * @return string
     */
    protected function generateRequestUrl(string $endpoint, int $steamUserId): string
    {
        $separator = '?';

        if (strpos($endpoint, $separator) !== false) {
            $separator = '&';
        }

        return 'http://api.steampowered.com' . $endpoint . $separator .
            'key=' . getenv('STEAM_API_KEY') . '&steamid=' . $steamUserId . '&format=json&l=english';
    }
}
