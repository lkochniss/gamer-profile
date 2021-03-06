<?php

namespace App\Service\Api;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
     * @var int
     */
    private $cacheExpiration;

    /**
     * @var
     */
    private $failureExpiration;

    /**
     * GameApiClientService constructor.
     *
     * @param GuzzleClient $guzzleClient
     * @param int $cacheExpiration
     * @param int $failureExpiration
     */
    public function __construct(GuzzleClient $guzzleClient, int $cacheExpiration = 600, $failureExpiration = 60)
    {
        $this->guzzleClient = $guzzleClient;
        $this->cacheExpiration = $cacheExpiration;
        $this->failureExpiration = $failureExpiration;
    }

    /**
     * @param string $endpoint
     * @param int $steamUserId
     * @return array
     */
    public function get(string $endpoint, int $steamUserId): array
    {
        $cache = new FilesystemAdapter();
        $cacheKey = $this->generateCacheKey($endpoint, $steamUserId);

        try {
            $cacheObject = $cache->getItem($cacheKey);
        } catch (\Psr\Cache\InvalidArgumentException $exception) {
            return [];
        }

        if ($cacheObject->isHit()) {
            return $cacheObject->get();
        }

        try {
            $response = $this->guzzleClient->request(
                'GET',
                $this->generateRequestUrl($endpoint, $steamUserId),
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ]
                ]
            );

            $cacheObject->set(json_decode($response->getBody(), true));
            $cacheObject->expiresAfter($this->cacheExpiration);
        } catch (\GuzzleHttp\Exception\GuzzleException $exception) {
            $cacheObject->set([]);
            $cacheObject->expiresAfter($this->failureExpiration);
        }

        $cache->save($cacheObject);

        return $cacheObject->get();
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

    /**
     * @param string $endpoint
     * @param string $steamUserId
     * @return string
     */
    private function generateCacheKey(string $endpoint, string $steamUserId): string
    {
        $url = $this->generateRequestUrl($endpoint, $steamUserId);
        return sprintf('steam-user-api.%s', md5($url));
    }
}
