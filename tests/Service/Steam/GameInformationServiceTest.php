<?php

namespace tests\App\Service\Steam;

use App\Service\Steam\Api\GameApiClientService;
use App\Service\Steam\GameInformationService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GameInformationServiceTest
 */
class GameInformationServiceTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $steamGameApiServiceMock;

    public function setUp(): void
    {
        $this->steamGameApiServiceMock = $this->createMock(GameApiClientService::class);
    }

    public function testGetInformationForAppId(): void
    {
        $this->setSteamGameApiClientMock();

        $gameInformationService = new GameInformationService($this->steamGameApiServiceMock);
        $gameInformation = $gameInformationService->getInformationForAppId(1);

        $this->assertEquals($this->getGameArray(), $gameInformation);
    }

    private function setSteamGameApiClientMock(): void
    {
        $this->steamGameApiServiceMock->expects($this->any())
            ->method('get')
            ->with('/api/appdetails?appids=1')
            ->willReturn(new JsonResponse($this->getGameResponseData()));
    }

    /**
     * @return array
     */
    private function getGameResponseData(): array
    {
        return [
            '1' => [
                'success' => true,
                'data' => $this->getGameArray()
            ]
        ];
    }

    /**
     * @return array
     */
    private function getGameArray(): array
    {
        return[
            'type' => 'game',
            'name' => 'Demo game',
            'steam_appid' => 1,
            'required_age' => 0,
        ];
    }
}
