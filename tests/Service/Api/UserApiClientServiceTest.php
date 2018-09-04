<?php

namespace tests\App\Service\Steam\Api;

use App\Service\Api\UserApiClientService;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class UserApiClientServiceTest
 */
class UserApiClientServiceTest extends TestCase
{

    /**
     * @param string $endpoint
     * @param string $expectedUrl
     *
     * @dataProvider urlProvider
     */
    public function testGet(string $endpoint, string $expectedUrl): void
    {
        $guzzleClientMock = $this->createMock(GuzzleClient::class);
        $guzzleClientMock->expects($this->any())
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
            ->willReturn(new Response());

        $steamApiClient = new UserApiClientService($guzzleClientMock);

        $this->assertEquals(new Response(), $steamApiClient->get($endpoint, 1));
    }

    public function urlProvider(): array
    {
        return [
            ['/', 'http://api.steampowered.com/?key=unittest&steamid=1&format=json&l=english'],
            ['/1?asd=2', 'http://api.steampowered.com/1?asd=2&key=unittest&steamid=1&format=json&l=english'],
        ];
    }
}
