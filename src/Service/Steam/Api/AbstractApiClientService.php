<?php

namespace App\Service\Steam\Api;

use GuzzleHttp\Psr7\Response;
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
     * @return Response
     */
    public function get($endpoint) : Response
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
     * @return string
     */
    abstract protected function getBasePath() : string;

    /**
     * @param $endpoint
     *
     * @return string
     */
    abstract protected function generateRequestUrl($endpoint) : string;
}
