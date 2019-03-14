<?php

namespace App\Tests\Service;

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

    public function testFetchNewGameShouldCallGameInformationService()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn(null);

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
}
