<?php

namespace App\Tests\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Steam\CreateGameService;
use App\Service\Transformation\GameInformationService;
use PHPUnit\Framework\TestCase;

class CreateGameServiceTest extends TestCase
{
    private $steamAppId = 1;

    public function testCreateGameServiceShouldCallGameRepository(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId');
        $gameInformationServiceMock = $this->createMock(GameInformationService::class);

        $createGameService = new CreateGameService($gameRepositoryMock, $gameInformationServiceMock);
        $createGameService->execute($this->steamAppId);
    }

    public function testCreateGameServiceShouldNotCallGameInformationServiceIfGameExists(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(new Game());

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->never())
            ->method('getGameInformationForSteamAppId');

        $createGameService = new CreateGameService($gameRepositoryMock, $gameInformationServiceMock);
        $createGameService->execute($this->steamAppId);
    }

    public function testCreateGameServiceShouldCallGameInformationService(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(null);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->once())
            ->method('getGameInformationForSteamAppId');

        $createGameService = new CreateGameService($gameRepositoryMock, $gameInformationServiceMock);
        $createGameService->execute($this->steamAppId);
    }

    public function testCreateGameServiceShouldPersistFailedGame(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(null);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->once())
            ->method('getGameInformationForSteamAppId')
            ->willReturn([]);

        $expectedGame = new Game();
        $expectedGame->setSteamAppId($this->steamAppId);
        $expectedGame->setName(Game::NAME_FAILED);
        $expectedGame->setHeaderImagePath(Game::IMAGE_FAILED);

        $gameRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedGame);

        $createGameService = new CreateGameService($gameRepositoryMock, $gameInformationServiceMock);
        $createGameService->execute($this->steamAppId);
    }

    public function testCreateGameServiceShouldPersistGame(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(null);

        $expectedGame = new Game();
        $expectedGame->setSteamAppId($this->steamAppId);
        $expectedGame->setName('just a game');
        $expectedGame->setHeaderImagePath('image.jpg');

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->once())
            ->method('getGameInformationForSteamAppId')
            ->willReturn([
                'name' => $expectedGame->getName(),
                'header_image' => $expectedGame->getHeaderImagePath()
            ]);

        $gameRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedGame);

        $createGameService = new CreateGameService($gameRepositoryMock, $gameInformationServiceMock);
        $createGameService->execute($this->steamAppId);
    }
}
