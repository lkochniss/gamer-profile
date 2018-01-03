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

    /**
     * @var MockObject
     */
    private $gameMock;

    public function setUp(): void
    {
        $this->steamUserApiServiceMock = $this->createMock(UserApiClientService::class);
        $this->steamGameInformationServiceMock = $this->createMock(GameInformationService::class);
        $this->gameRepositoryMock = $this->createMock(GameRepository::class);
        $this->gameMock = $this->createMock(Game::class);
    }

    public function testGetAllMyGames(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwned = $gamesOwnedService->getAllMyGames();

        $this->assertEquals($this->getGamesArray(), $gamesOwned);
    }

    public function testGetMyRecentlyPlayedGames(): void
    {
        $this->setRecentGamesSteamUserApiClientMock();

        $gamesOwnedService = $this->getGamesOwnedService();
        $recentlyPlayedGames = $gamesOwnedService->getMyRecentlyPlayedGames();

        $this->assertEquals($this->getRecentlyPlayedGamesArray(), $recentlyPlayedGames);
    }

    public function testCreateOrUpdateGameWithoutExistingGame(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();
        $this->setGameInformationServiceMockWithGame();
        $this->setGameRepositoryMockWithoutGame();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwnedService->getAllMyGames();

        $this->assertEquals('N', $gamesOwnedService->createOrUpdateGame('1'));
    }

    public function testCreateOrUpdateGameWithExistingGame(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();
        $this->setGameInformationServiceMockWithGame();
        $this->setGameRepositoryMockWithGame();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwnedService->getAllMyGames();

        $this->assertEquals('U', $gamesOwnedService->createOrUpdateGame('1'));
    }

    public function testCreateGameIfNotExistWithoutExistingGame(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();
        $this->setGameInformationServiceMockWithGame();
        $this->setGameRepositoryMockWithoutGame();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwnedService->getAllMyGames();

        $this->assertEquals('N', $gamesOwnedService->createGameIfNotExist('1'));
    }

    public function testCreateGameIfNotExistWithExistingGame(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();
        $this->setGameInformationServiceMockWithGame();
        $this->setGameRepositoryMockWithGame();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwnedService->getAllMyGames();

        $this->assertEquals('S', $gamesOwnedService->createGameIfNotExist('1'));
    }

    public function testCreateOrUpdateGameFailure(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();
        $this->setGameInformationServiceMockWithoutGame();
        $this->setGameRepositoryMockWithoutGame();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwnedService->getAllMyGames();

        $this->assertEquals('F', $gamesOwnedService->createOrUpdateGame('1'));
    }

    public function testGetEmptySummary(): void
    {
        $gamesOwnedService = $this->getGamesOwnedService();
        $this->assertEquals([], $gamesOwnedService->getSummary());
    }

    public function testGetSuccessSummary(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();
        $this->setGameInformationServiceMockWithGame();
        $this->setGameRepositoryMockWithoutGame();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwnedService->getAllMyGames();
        $gamesOwnedService->createOrUpdateGame('1');

        $this->assertEquals(['Added %s new games' => 1], $gamesOwnedService->getSummary());
    }

    public function testGetErrors(): void
    {
        $this->setGamesOwnedSteamUserApiClientMock();
        $this->setGameInformationServiceMockWithoutGame();
        $this->setGameRepositoryMockWithoutGame();

        $gamesOwnedService = $this->getGamesOwnedService();
        $gamesOwnedService->getAllMyGames();
        $gamesOwnedService->createOrUpdateGame('1');

        $this->assertEquals([1], $gamesOwnedService->getErrors());
    }

    public function testResetRecentGames(): void
    {
        $this->setGameRepositoryRecentlyPlayedGame();
        $gamesOwnedService = $this->getGamesOwnedService();
        $this->assertTrue($gamesOwnedService->resetRecentGames());
    }

    private function setGamesOwnedSteamUserApiClientMock(): void
    {
        $this->steamUserApiServiceMock->expects($this->any())
            ->method('get')
            ->with('/IPlayerService/GetOwnedGames/v0001/')
            ->willReturn(new Response(200, [], json_encode($this->getOwnedGamesResponseData())));
    }

    private function setRecentGamesSteamUserApiClientMock(): void
    {
        $this->steamUserApiServiceMock->expects($this->any())
            ->method('get')
            ->with('/IPlayerService/GetRecentlyPlayedGames/v0001/')
            ->willReturn(new Response(200, [], json_encode($this->getRecentlyPlayedGamesResponseData())));
    }

    private function setGameInformationServiceMockWithGame(): void
    {
        $this->steamGameInformationServiceMock->expects($this->any())
            ->method('getInformationForAppId')
            ->willReturn($this->getGameInformation());
    }

    private function setGameInformationServiceMockWithoutGame(): void
    {
        $this->steamGameInformationServiceMock->expects($this->any())
            ->method('getInformationForAppId')
            ->willReturn([]);
    }

    private function setGameRepositoryMockWithGame(): void
    {
        $game = new Game();
        $game->setSteamAppId(1);
        $game->setName('Demo game');
        $this->gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->willReturn($game);
    }

    private function setGameRepositoryMockWithoutGame(): void
    {
        $this->gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->willReturn(null);
    }

    private function setGameRepositoryRecentlyPlayedGame(): void
    {
        $this->gameMock->expects($this->any())
            ->method('setRecentlyPlayed')
            ->with(0);
        $this->gameRepositoryMock->expects($this->any())
            ->method('getRecentlyPlayedGames')
            ->willReturn([$this->gameMock]);
    }

    /**
     * @return array
     */
    private function getOwnedGamesResponseData(): array
    {
        return [
            'response' => [
                'games_count' => 1,
                'games' => $this->getGamesArray()
            ]
        ];
    }

    /**
     * @return array
     */
    private function getRecentlyPlayedGamesResponseData(): array
    {
        return [
            'response' => [
                'games_count' => 1,
                'games' => $this->getRecentlyPlayedGamesArray()
            ]
        ];
    }

    /**
     * @return array
     */
    private function getRecentlyPlayedGamesArray(): array
    {
        return [
            1 => [
                'appid' => 1,
                'playtime_forever' => 0,
                'playtime_2weeks' => 216
            ]
        ];
    }

    /**
     * @return array
     */
    private function getGamesArray(): array
    {
        return [
            1 => [
                'appid' => 1,
                'playtime_forever' => 0
            ]
        ];
    }

    /**
     * @return array
     */
    private function getGameInformation(): array
    {
        return[
            'type' => 'game',
            'name' => 'Demo game',
            'steam_appid' => 1,
            'header_image' => 'http://header.image/src.jpg',
        ];
    }

    /**
     * @return GamesOwnedService
     */
    private function getGamesOwnedService(): GamesOwnedService
    {
        return new GamesOwnedService(
            $this->steamUserApiServiceMock,
            $this->steamGameInformationServiceMock,
            $this->gameRepositoryMock
        );
    }
}
