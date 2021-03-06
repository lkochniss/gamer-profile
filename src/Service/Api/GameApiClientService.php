<?php

namespace App\Service\Api;

use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
    public function __construct(GuzzleClient $guzzleClient, int $cacheExpiration = 3600, $failureExpiration = 60)
    {
        $this->guzzleClient = $guzzleClient;
        $this->cacheExpiration = $cacheExpiration;
        $this->failureExpiration = $failureExpiration;
    }

    /**
     * @param string $endpoint
     * @return array
     */
    public function get(string $endpoint): array
    {
        $cache = new FilesystemAdapter();
        $cacheKey = $this->generateCacheKey($endpoint);

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
                $this->generateRequestUrl($endpoint),
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
     * @return string
     */
    protected function generateRequestUrl(string $endpoint): string
    {
        $separator = '?';

        if (strpos($endpoint, $separator) !== false) {
            $separator = '&';
        }

        return 'https://store.steampowered.com' . $endpoint . $separator . 'l=english';
    }

    /**
     * @param string $endpoint
     * @return string
     */
    private function generateCacheKey(string $endpoint): string
    {
        return sprintf('steam-game-api.%s', md5($endpoint));
    }
}
