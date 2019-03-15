<?php

namespace App\Tests\Service;

use App\Service\Steam\CreateGameService;
use App\Service\Steam\CreateGamesForUserService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class CreateGamesForUserServiceTest extends TestCase
{
    public function testCreateGamesForUsersShouldCallGameUserInformationService(): void
    {
        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1);

        $createGameServiceMock = $this->createMock(CreateGameService::class);

        $createGamesForUserService = new CreateGamesForUserService($gameUserInformationServiceMock, $createGameServiceMock);
        $createGamesForUserService->execute(1);
    }

    public function testCreateGamesForUsersShouldNotCallCreateGameServiceOnEmptyGames(): void
    {
        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([]);

        $createGameServiceMock = $this->createMock(CreateGameService::class);
        $createGameServiceMock->expects($this->never())
            ->method('execute');

        $createGamesForUserService = new CreateGamesForUserService($gameUserInformationServiceMock, $createGameServiceMock);
        $createGamesForUserService->execute(1);
    }

    public function testCreateGamesForUsersShouldCallCreateGameService(): void
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

        $createGameServiceMock = $this->createMock(CreateGameService::class);
        $createGameServiceMock->expects($this->once())
            ->method('execute')
            ->with(42);

        $createGamesForUserService = new CreateGamesForUserService($gameUserInformationServiceMock, $createGameServiceMock);
        $createGamesForUserService->execute(1);
    }
}
