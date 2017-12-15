<?php

namespace tests\App\Service\Steam;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\ReportService;
use App\Service\Steam\GameInformationService;
use App\Service\Steam\GamesOwnedService;
use App\Service\Steam\Api\UserApiClientService;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class GamesOwnedServiceTest
 */
class GamesOwnedServiceTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $steamUserApiServiceMock;

    /**
     * @var MockObject
     */
    private $steamGameInformationServiceMock;

    /**
     * @var MockObject
     */
    private $gameRepositoryMock;

    public function setUp(): void
    {
        $this->steamUserApiServiceMock = $this->createMock(UserApiClientService::class);
        $this->steamGameInformationServiceMock = $this->createMock(GameInformationService::class);
        $this->gameRepositoryMock = $this->createMock(GameRepository::class);
    }

    public function testGetMyGames(): void
    {
        $this->setSteamUserApiClientMock();

        $gamesOwnedService = $this->getGamesOwendService();
        $gamesOwned = $gamesOwnedService->getMyGames();

        $this->assertEquals($this->getGamesArray(), $gamesOwned);
    }

    private function setSteamUserApiClientMock(): void
    {
        $this->steamUserApiServiceMock->expects($this->any())
            ->method('get')
            ->with('/IPlayerService/GetOwnedGames/v0001/')
            ->willReturn(new Response(200, [], json_encode($this->getOwnedGamesResponseData())));
    }

    private function setSteamGameInformationServiceMock(): void
    {
        $this->steamGameInformationServiceMock->expects($this->any())
            ->method('getInformationForAppId')
            ->willReturn($this->getGameInformationArray());
    }

    private function setGameRepositoryMockWithGame(): void
    {
        $this->gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn(new Game());
    }

    private function setGameRepositoryMockWithoutGame(): void
    {
        $this->gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn(null);
    }

    /**
     * @return array
     */
    private function getOwnedGamesResponseData(): array
    {
        return [
            'response' => [
                'games_count' => 2,
                'games' => $this->getGamesArray()
            ]
        ];
    }

    /**
     * @return array
     */
    private function getGamesArray(): array
    {
        return [
            [
                'appid' => 1,
                'playtime_forever' => 0
            ]
        ];
    }

    /**
     * @return array
     */
    private function getGameInformationArray(): array
    {
        return [
            'type' => 'game',
            'name' => 'Demo game',
            'steam_appid' => 1,
            'required_age' => 0,
        ];
    }

    /**
     * @return GamesOwnedService
     */
    private function getGamesOwendService(): GamesOwnedService
    {
        return new GamesOwnedService(
            $this->steamUserApiServiceMock,
            $this->steamGameInformationServiceMock,
            $this->gameRepositoryMock
        );
    }
}
