<?php

namespace tests\App\Service\Api;

use App\Service\Api\GameApiClientService;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class GameApiClientServiceTest
 */
class GameApiClientServiceTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(GuzzleClient::class);
    }

    public function testGetAnswersWithArray(): void
    {
        $this->setGuzzleClientMock();
        $steamApiClient = new GameApiClientService($this->client, 0);

        $this->assertEquals(['success' => true], $steamApiClient->get('uncached-endpoint'));
    }

    public function testGetAnswersWithCacheOnSecondCall(): void
    {
        $response = new Response(200, [], json_encode(['success' => true]));
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $steamApiClient = new GameApiClientService($this->client, 2);

        $steamApiClient->get('cached-endpoint');
        $steamApiClient->get('cached-endpoint');
        $steamApiClient->get('cached-endpoint');
    }

    private function setGuzzleClientMock(): void
    {
        $response = new Response(200, [], json_encode(['success' => true]));

        $this->client->expects($this->any())
            ->method('request')
            ->willReturn($response);
    }
}
