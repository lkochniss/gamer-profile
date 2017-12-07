<?php

namespace App\Service\Steam\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class AbstractApiClientService
 */
abstract class AbstractApiClientService
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
     * @return JsonResponse
     */
    public function get($endpoint) : JsonResponse
    {
        return $this->guzzleClient->request(
            'GET',
            $this->generateRequestUrl($endpoint),
            array(
                'headers' => array(
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ),
            )
        );
    }

    /**
     * @return string
     */
    protected abstract function getBasePath() : string;

    /**
     * @param $endpoint
     *
     * @return string
     */
    protected abstract function generateRequestUrl($endpoint) : string;
}
