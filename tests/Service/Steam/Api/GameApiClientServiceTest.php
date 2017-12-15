<?php

namespace tests\App\Service\Steam\Api;

use App\Service\Steam\Api\UserApiClientService;
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
        $steamApiClient = new UserApiClientService($this->client);

        $this->assertEquals(new Response(), $steamApiClient->get(''));
    }

    private function setGuzzleClientMock(): void
    {
        $this->client->expects($this->any())
            ->method('request')
            ->willReturn(new Response());
    }
}
