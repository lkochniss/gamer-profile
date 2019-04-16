<?php

namespace tests\App\Service\Steam;

use App\Entity\JSON\JsonGame;
use App\Service\Api\GameApiClientService;
use App\Service\Transformation\GameInformationService;
use PHPUnit\Framework\TestCase;

/**
 * Class GameInformationServiceTest
 */
class GameInformationServiceTest extends TestCase
{
    public function testGetGameInformationEntityForSteamAppIdShouldCallTheClientCorrectly(): void
    {
        $steamAppId = 1;
        $apiMock = $this->createMock(GameApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/api/appdetails?appids=' . $steamAppId
            );

        $service = new GameInformationService($apiMock);
        $service->getGameInformationEntityForSteamAppId($steamAppId);
    }

    public function testGetGameInformationEntityForSteamAppIdShouldReturnEmptyArrayOnEmptyGame(): void
    {
        $steamAppId = 1;
        $apiMock = $this->createMock(GameApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/api/appdetails?appids=' . $steamAppId
            )
            ->willReturn([]);

        $service = new GameInformationService($apiMock);
        $this->assertEquals(
            new JsonGame([]),
            $service->getGameInformationEntityForSteamAppId($steamAppId)
        );
    }

    public function testGetGameInformationEntityForSteamAppIdShouldReturnEmptyArrayOnFailure(): void
    {
        $steamAppId = 1;
        $apiMock = $this->createMock(GameApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/api/appdetails?appids=' . $steamAppId
            )
            ->willReturn([
                $steamAppId => [
                    'success' => false
                ]
            ]);

        $service = new GameInformationService($apiMock);
        $this->assertEquals(
            new JsonGame([]),
            $service->getGameInformationEntityForSteamAppId($steamAppId)
        );
    }

    public function testGetGameInformationEntityForSteamAppIdShouldReturnJsonGame(): void
    {
        $steamAppId = 1;
        $response = [
            'name' => 'The Game Name',
            'header_image' => 'image.png'
        ];

        $apiMock = $this->createMock(GameApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/api/appdetails?appids=' . $steamAppId
            )
            ->willReturn([
                $steamAppId => [
                    'success' => true,
                    'data' => $response
                ]
            ]);

        $service = new GameInformationService($apiMock);
        $this->assertEquals(
            new JsonGame($response),
            $service->getGameInformationEntityForSteamAppId($steamAppId)
        );
    }
}
