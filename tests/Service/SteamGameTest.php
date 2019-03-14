<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SteamGameService;
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

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $steamGameService = new SteamGameService($userRepositoryMock, $gameUserInformationServiceMock);
        $steamGameService->fetchNewGames();
    }

    public function testFetchNewGameShouldCallGameUSerInformationService()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with(1)
            ->willReturn([]);

        $steamGameService = new SteamGameService($userRepositoryMock, $gameUserInformationServiceMock);
        $steamGameService->fetchNewGames();
    }
}
