<?php

namespace App\Tests\Service;

use App\Service\Steam\GameService;
use App\Service\Steam\GamesForUserService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class GamesForUserServiceTest extends TestCase
{
    public function testGamesForUsersCreateShouldCallGameUserInformationService(): void
    {
        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1);

        $createGameServiceMock = $this->createMock(GameService::class);

        $createGamesForUserService = new GamesForUserService($gameUserInformationServiceMock, $createGameServiceMock);
        $createGamesForUserService->create(1);
    }

    public function testGamesForUsersCreateShouldNotCallCreateGameServiceOnEmptyGames(): void
    {
        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([]);

        $createGameServiceMock = $this->createMock(GameService::class);
        $createGameServiceMock->expects($this->never())
            ->method('create');

        $createGamesForUserService = new GamesForUserService($gameUserInformationServiceMock, $createGameServiceMock);
        $createGamesForUserService->create(1);
    }

    public function testGamesForUsersCreateShouldCallCreateGameService(): void
    {
        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([
                [
                    'appid' => 42
                ]
            ]);

        $createGameServiceMock = $this->createMock(GameService::class);
        $createGameServiceMock->expects($this->once())
            ->method('create')
            ->with(42);

        $createGamesForUserService = new GamesForUserService($gameUserInformationServiceMock, $createGameServiceMock);
        $createGamesForUserService->create(1);
    }
}
