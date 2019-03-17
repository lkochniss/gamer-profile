<?php

namespace App\Tests\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Steam\GameService;
use App\Service\Transformation\GameInformationService;
use PHPUnit\Framework\TestCase;

class GameServiceTest extends TestCase
{
    private $steamAppId = 1;

    public function testGameServiceCreateShouldCallGameRepository(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId');
        $gameInformationServiceMock = $this->createMock(GameInformationService::class);

        $GameServiceCreate = new GameService($gameRepositoryMock, $gameInformationServiceMock);
        $GameServiceCreate->create($this->steamAppId);
    }

    public function testGameServiceCreateShouldNotCallGameInformationServiceIfGameExists(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(new Game(1));

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->never())
            ->method('getGameInformationForSteamAppId');

        $GameServiceCreate = new GameService($gameRepositoryMock, $gameInformationServiceMock);
        $GameServiceCreate->create($this->steamAppId);
    }

    public function testGameServiceCreateShouldCallGameInformationService(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(null);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->once())
            ->method('getGameInformationForSteamAppId');

        $GameServiceCreate = new GameService($gameRepositoryMock, $gameInformationServiceMock);
        $GameServiceCreate->create($this->steamAppId);
    }

    public function testGameServiceCreateShouldPersistFailedGame(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(null);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->once())
            ->method('getGameInformationForSteamAppId')
            ->willReturn([]);

        $expectedGame = new Game($this->steamAppId);
        $expectedGame->setName(Game::NAME_FAILED);
        $expectedGame->setHeaderImagePath(Game::IMAGE_FAILED);

        $gameRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedGame);

        $GameServiceCreate = new GameService($gameRepositoryMock, $gameInformationServiceMock);
        $GameServiceCreate->create($this->steamAppId);
    }

    public function testGameServiceCreateShouldPersistGame(): void
    {
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->willReturn(null);

        $expectedGame = new Game($this->steamAppId);
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

        $GameServiceCreate = new GameService($gameRepositoryMock, $gameInformationServiceMock);
        $GameServiceCreate->create($this->steamAppId);
    }
}
