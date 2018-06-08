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

    public function testGet(): void
    {
        $this->setGuzzleClientMock();
        $steamApiClient = new GameApiClientService($this->client);

        $this->assertEquals(new Response(), $steamApiClient->get(''));
    }

    private function setGuzzleClientMock(): void
    {
        $this->client->expects($this->any())
            ->method('request')
            ->willReturn(new Response());
    }
}
