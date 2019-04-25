<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Service\GameStats\PlaytimeService;
use App\Service\GameStats\UpdatePlaytimeForUserService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class UpdatePlaytimeForUserServiceTest extends TestCase
{
    public function testExecuteShouldResetRecentlyPlayedGames(): void
    {
        $user = new User();
        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $playtimeServiceMock =  $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('resetRecentPlaytimeForUser')
            ->with($user);

        $repositoryMock = $this->createMock(GameRepository::class);

        $service = new UpdatePlaytimeForUserService(
            $informationServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );
        $service->execute($user);
    }

    public function testExecuteShouldCallForRecentlyPlayedGames(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId());

        $playtimeServiceMock =  $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('resetRecentPlaytimeForUser')
            ->with($user);

        $repositoryMock = $this->createMock(GameRepository::class);

        $service = new UpdatePlaytimeForUserService(
            $informationServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );
        $service->execute($user);
    }

    public function testExecuteShouldFindGameForRecentlyPlayed(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => 2
                ]
            ]);

        $playtimeServiceMock =  $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('resetRecentPlaytimeForUser')
            ->with($user);

        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2);

        $service = new UpdatePlaytimeForUserService(
            $informationServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );
        $service->execute($user);
    }

    public function testExecuteShouldSkipWithoutGameEntity(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => 2
                ]
            ]);

        $playtimeServiceMock =  $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('resetRecentPlaytimeForUser')
            ->with($user);

        $playtimeServiceMock->expects($this->never())
            ->method('updateGameForUser');

        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2)
            ->willReturn(null);

        $service = new UpdatePlaytimeForUserService(
            $informationServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );
        $service->execute($user);
    }

    public function testExecuteShouldCallPlaytimeService(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);

        $informationServiceMock = $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => $game->getSteamAppId()
                ]
            ]);

        $playtimeServiceMock =  $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('resetRecentPlaytimeForUser')
            ->with($user);

        $playtimeServiceMock->expects($this->once())
            ->method('updateGameForUser')
            ->with($game, $user);

        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with($game->getSteamAppId())
            ->willReturn($game);

        $service = new UpdatePlaytimeForUserService(
            $informationServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );
        $service->execute($user);
    }
}
