<?php

namespace tests\App\Service\Steam\Api;

use App\Service\Api\UserApiClientService;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class UserApiClientServiceTest
 */
class UserApiClientServiceTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(GuzzleClient::class);
    }

    public function urlProvider(): array
    {
        return [
            ['/', 'http://api.steampowered.com/?key=unittest&steamid=1&format=json&l=english'],
            ['/1?asd=2', 'http://api.steampowered.com/1?asd=2&key=unittest&steamid=1&format=json&l=english'],
        ];
    }

    /**
     * @param string $endpoint
     * @param string $expectedUrl
     *
     * @dataProvider urlProvider
     */
    public function testGetShouldReturnArrayOfResponse(string $endpoint, string $expectedUrl): void
    {
        $response = new Response(200, [], json_encode(['success' => true]));

        $this->client->expects($this->any())
            ->method('request')
            ->with(
                'GET',
                $expectedUrl,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ]
                ]
            )
            ->willReturn($response);

        $steamApiClient = new UserApiClientService($this->client, 0);

        $this->assertEquals(['success' => true], $steamApiClient->get($endpoint, 1));
    }

    public function testGetShouldReturnCacheOnSecondCall(): void
    {
        $response = new Response(200, [], json_encode(['success' => true]));

        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $steamApiClient = new UserApiClientService($this->client, 2);

        $steamApiClient->get('cached-endpoint', 1);
        $steamApiClient->get('cached-endpoint', 1);
    }

    public function testGetShouldCacheMultipleEndpoints(): void
    {
        $response = new Response(200, [], json_encode(['success' => true]));

        $this->client->expects($this->exactly(3))
            ->method('request')
            ->willReturn($response);

        $steamApiClient = new UserApiClientService($this->client, 2);

        $steamApiClient->get('multiple-cached-endpoint', 1);
        $steamApiClient->get('multiple-cached-endpoint', 1);
        $steamApiClient->get('multiple-cached-endpoints', 1);
        $steamApiClient->get('multiple-cached-endpoints', 1);
        $steamApiClient->get('multiple-cached-endpoint', 2);
        $steamApiClient->get('multiple-cached-endpoint', 2);
    }
}
