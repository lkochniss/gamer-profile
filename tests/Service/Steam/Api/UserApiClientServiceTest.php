<?php

namespace tests\App\Service\Steam\Api;

use App\Service\Steam\Api\UserApiClientService;
use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UserApiClientServiceTest
 */
class UserApiClientServiceTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $guzzleClientMock;

    public function setUp(): void
    {
        $this->guzzleClientMock = $this->createMock(GuzzleClient::class);
    }

    public function testGet(): void
    {
        $this->setGuzzleClientMock();
        $steamApiClient = new UserApiClientService($this->guzzleClientMock);

        $this->assertEquals(new JsonResponse('ok'), $steamApiClient->get(''));
    }

    private function setGuzzleClientMock(): void
    {
        $this->guzzleClientMock->expects($this->any())
            ->method('request')
            ->willReturn(new JsonResponse('ok'));
    }
}
