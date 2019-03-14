<?php

namespace App\Tests\Service;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Service\SteamGameService;
use App\Service\Transformation\GameInformationService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class SteamGameTest extends TestCase
{
    public function testFetchNewGameShouldCallUserRepository()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameInformationServiceMock = $this->createMock(GameInformationService::class);

        $steamGameService = new SteamGameService(
            $userRepositoryMock,
            $gameRepositoryMock,
            $gameUserInformationServiceMock,
            $gameInformationServiceMock
        );
        $steamGameService->fetchNewGames();
    }

    public function testFetchNewGameShouldCallGameUserInformationService()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([]);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);

        $steamGameService = new SteamGameService(
            $userRepositoryMock,
            $gameRepositoryMock,
            $gameUserInformationServiceMock,
            $gameInformationServiceMock
        );
        $steamGameService->fetchNewGames();
    }

    public function testFetchNewGameShouldReturnExistingGame()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(21)
            ->willReturn(new Game());

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([
                'response' => [
                    'games' => [
                        [
                            'appid' => 21
                        ]
                    ]
                ]
            ]);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->never())
            ->method('getGameInformationForSteamAppId');

        $steamGameService = new SteamGameService(
            $userRepositoryMock,
            $gameRepositoryMock,
            $gameUserInformationServiceMock,
            $gameInformationServiceMock
        );
        $steamGameService->fetchNewGames();
    }

    public function testFetchNewGameShouldCallGameInformationService()
    {
        $expectedGame = new Game();
        $expectedGame->setSteamAppId(21);
        $expectedGame->setName(Game::NAME_FAILED);
        $expectedGame->setHeaderImagePath(Game::IMAGE_FAILED);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(21)
            ->willReturn(null);
        $gameRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedGame);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([
                'response' => [
                    'games' => [
                        [
                            'appid' => 21
                        ]
                    ]
                ]
            ]);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->once())
            ->method('getGameInformationForSteamAppId')
            ->with(21)
            ->willReturn([]);

        $steamGameService = new SteamGameService(
            $userRepositoryMock,
            $gameRepositoryMock,
            $gameUserInformationServiceMock,
            $gameInformationServiceMock
        );
        $steamGameService->fetchNewGames();
    }

    public function testFetchNewGameShouldPersistANewGame()
    {
        $expectedGame = new Game();
        $expectedGame->setSteamAppId(21);
        $expectedGame->setName('game');
        $expectedGame->setHeaderImagePath('image.png');

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with($expectedGame->getSteamAppId())
            ->willReturn(null);
        $gameRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedGame);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([
                'response' => [
                    'games' => [
                        [
                            'appid' => $expectedGame->getSteamAppId()
                        ]
                    ]
                ]
            ]);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->once())
            ->method('getGameInformationForSteamAppId')
            ->with($expectedGame->getSteamAppId())
            ->willReturn([
                'name' => $expectedGame->getName(),
                'steam_app_id' => $expectedGame->getSteamAppId(),
                'header_image' => $expectedGame->getHeaderImagePath(),
            ]);

        $steamGameService = new SteamGameService(
            $userRepositoryMock,
            $gameRepositoryMock,
            $gameUserInformationServiceMock,
            $gameInformationServiceMock
        );
        $steamGameService->fetchNewGames();
    }
}
