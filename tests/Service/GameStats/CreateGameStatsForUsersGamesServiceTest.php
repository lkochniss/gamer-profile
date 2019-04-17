<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Service\GameStats\CreateGameStatsForUsersGamesService;
use App\Service\GameStats\CreateGameStatsService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class CreateGameStatsForUsersGamesServiceTest extends TestCase
{
    public function testExecuteShouldGetAllGames(): void
    {
        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $statsServiceMock = $this->createMock(CreateGameStatsService::class);
        $repositoryMock = $this->createMock(GameRepository::class);

        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn([]);

        $service = new CreateGameStatsForUsersGamesService(
            $informationServiceMock,
            $statsServiceMock,
            $repositoryMock
        );

        $service->execute($user);
    }

    public function testExecuteShouldFindAGame(): void
    {
        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $statsServiceMock = $this->createMock(CreateGameStatsService::class);
        $repositoryMock = $this->createMock(GameRepository::class);

        $user = new User();
        $user->setSteamId(1);

        $steamAppId = 12;

        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => $steamAppId
                ]
            ]);

        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with($steamAppId);

        $service = new CreateGameStatsForUsersGamesService(
            $informationServiceMock,
            $statsServiceMock,
            $repositoryMock
        );

        $service->execute($user);
    }

    public function testExecuteShouldSkipOnNullGame(): void
    {
        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $statsServiceMock = $this->createMock(CreateGameStatsService::class);
        $repositoryMock = $this->createMock(GameRepository::class);

        $user = new User();
        $user->setSteamId(1);

        $steamAppId = 12;

        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => $steamAppId
                ]
            ]);

        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with($steamAppId);

        $statsServiceMock->expects($this->never())
            ->method('execute');

        $service = new CreateGameStatsForUsersGamesService(
            $informationServiceMock,
            $statsServiceMock,
            $repositoryMock
        );

        $service->execute($user);
    }

    public function testExecuteShouldCallExecuteOnGame(): void
    {
        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $statsServiceMock = $this->createMock(CreateGameStatsService::class);
        $repositoryMock = $this->createMock(GameRepository::class);

        $user = new User();
        $user->setSteamId(1);

        $steamAppId = 12;

        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => $steamAppId
                ]
            ]);

        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with($steamAppId)
            ->willReturn(new Game($steamAppId));

        $statsServiceMock->expects($this->once())
            ->method('execute')
            ->with(
                $user,
                new Game($steamAppId)
            );

        $service = new CreateGameStatsForUsersGamesService(
            $informationServiceMock,
            $statsServiceMock,
            $repositoryMock
        );

        $service->execute($user);
    }
}
