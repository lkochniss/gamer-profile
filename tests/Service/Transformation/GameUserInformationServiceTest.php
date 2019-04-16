<?php

namespace tests\App\Service\Steam;

use App\Entity\JSON\JsonAchievement;
use App\Entity\JSON\JsonPlaytime;
use App\Repository\GameSessionRepository;
use App\Service\Api\UserApiClientService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

/**
 * Class GameUserInformationServiceTest
 */
class GameUserInformationServiceTest extends TestCase
{

    public function setUp(): void
    {
    }

    public function testGetAllGamesShouldCallApiWithCorrectEndpoint(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            );

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $service->getAllGames($steamUserId);
    }

    public function testGetAllGamesShouldReturnAnArrayOfGames(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                    'games' => [
                        [
                            'appid' => 1,
                        ]
                    ]
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $actualGamesArray = $service->getAllGames($steamUserId);

        $this->assertEquals([1 => ['appid' => 1]], $actualGamesArray);
    }

    public function testGetAllGamesShouldReturnAnEmptyArrayOnMissingGamesAttribute(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $actualGamesArray = $service->getAllGames($steamUserId);

        $this->assertEquals([], $actualGamesArray);
    }

    public function testGetAllGamesShouldReturnAnEmptyArrayOnMissingResponseAttribute(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $actualGamesArray = $service->getAllGames($steamUserId);

        $this->assertEquals([], $actualGamesArray);
    }

    public function testGetRecentlyPlayedGamesShouldCallApiWithCorrectEndpoint(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetRecentlyPlayedGames/v0001/',
                $steamUserId
            );

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $service->getRecentlyPlayedGames($steamUserId);
    }

    public function testGetRecentlyPlayedGamesShouldReturnAnArrayOfGames(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetRecentlyPlayedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                    'games' => [
                        [
                            'appid' => 1,
                        ]
                    ]
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $actualGamesArray = $service->getRecentlyPlayedGames($steamUserId);

        $this->assertEquals([1 => ['appid' => 1]], $actualGamesArray);
    }

    public function testGetRecentlyPlayedGamesShouldReturnAnEmptyArrayOnMissingGamesAttribute(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetRecentlyPlayedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $actualGamesArray = $service->getRecentlyPlayedGames($steamUserId);

        $this->assertEquals([], $actualGamesArray);
    }

    public function testGetRecentlyPlayedGamesShouldReturnAnEmptyArrayOnMissingResponseAttribute(): void
    {
        $steamUserId = 1;
        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetRecentlyPlayedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $actualGamesArray = $service->getRecentlyPlayedGames($steamUserId);

        $this->assertEquals([], $actualGamesArray);
    }

    public function testGetAchievementsForGameShouldCallTheCorrectEndpoint(): void
    {
        $steamUserId = 1;
        $steamAppId = 2;

        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/ISteamUserStats/GetPlayerAchievements/v0001/?appid=' . $steamAppId,
                $steamUserId
            )
            ->willReturn([
                'response' => [
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $service->getAchievementsForGame($steamAppId, $steamUserId);
    }

    public function testGetAchievementsForGameShouldReturnAJsonAchievement(): void
    {
        $steamUserId = 1;
        $steamAppId = 2;

        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/ISteamUserStats/GetPlayerAchievements/v0001/?appid=' . $steamAppId,
                $steamUserId
            )
            ->willReturn([
                'response' => [
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);
        $actualJson = $service->getAchievementsForGame($steamAppId, $steamUserId);

        $this->assertEquals(new JsonAchievement([]), $actualJson);
    }

    public function testGetUserInformationForSteamAppIdShouldReturnGamesArray(): void
    {
        $steamUserId = 1;
        $steamAppId = 2;

        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                    'games' => [
                        [
                            'appid' => $steamAppId
                        ],
                        [
                            'appid' => 6
                        ]
                    ]
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);

        $this->assertEquals(['appid' => $steamAppId], $service->getUserInformationForSteamAppId($steamAppId, $steamUserId));
    }

    public function testGetUserInformationForSteamAppIdShouldReturnEmptyArrayOnMissingAppId(): void
    {
        $steamUserId = 1;
        $steamAppId = 2;

        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                    'games' => [
                        [
                            'appid' => 6
                        ]
                    ]
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);

        $this->assertEquals([], $service->getUserInformationForSteamAppId($steamAppId, $steamUserId));
    }

    public function testGetPlaytimeForGameShouldReturnJsonPlaytime(): void
    {
        $steamUserId = 1;
        $steamAppId = 2;

        $response = [
            'appid' => $steamAppId,
            'playtime_forever' => 10,
            'playtime_2weeks' => 5
        ];

        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                    'games' => [
                        $response
                    ]
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);

        $this->assertEquals(new JsonPlaytime($response), $service->getPlaytimeForGame($steamAppId, $steamUserId));
    }

    public function testGetPlaytimeForGameShouldReturnFallbackJsonPlaytime(): void
    {
        $steamUserId = 1;
        $steamAppId = 2;

        $response = [
            'appid' => $steamAppId,
        ];

        $apiMock = $this->createMock(UserApiClientService::class);
        $apiMock->expects($this->once())
            ->method('get')
            ->with(
                '/IPlayerService/GetOwnedGames/v0001/',
                $steamUserId
            )
            ->willReturn([
                'response' => [
                    'games' => [
                        $response
                    ]
                ]
            ]);

        $repositoryMock = $this->createMock(GameSessionRepository::class);

        $service = new GameUserInformationService($apiMock, $repositoryMock);

        $this->assertEquals(new JsonPlaytime(), $service->getPlaytimeForGame($steamAppId, $steamUserId));
    }
}
