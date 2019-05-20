<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\Playtime;
use App\Entity\User;
use App\Repository\GameStatsRepository;
use App\Service\GameStats\UpdateGameStatusForUserService;
use PHPUnit\Framework\TestCase;

class UpdateGameStatusForUserServiceTest extends TestCase
{
    public function testSetStatusPlayingShouldCallRepository(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('getByRecentlyPlayed')
            ->with($user->getSteamId());

        $service = new UpdateGameStatusForUserService($repositoryMock);
        $service->setStatusPlayingForRecentPlayed($user);
    }

    public function testSetStatusPlayingShouldSaveNewStatus(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $achievement = new Achievement($user->getSteamId(), $game);
        $playtime = new Playtime($user->getSteamId(), $game);

        $currentGameStats = new GameStats($user->getSteamId(), $game, $achievement, $playtime);
        $currentGameStats->setStatusPaused();

        $expectedGameStats = new GameStats($user->getSteamId(), $game, $achievement, $playtime);
        $expectedGameStats->setStatusPlaying();

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('getByRecentlyPlayed')
            ->with($user->getSteamId())
            ->willReturn([$currentGameStats]);

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedGameStats);

        $service = new UpdateGameStatusForUserService($repositoryMock);
        $service->setStatusPlayingForRecentPlayed($user);
    }

    public function testSetStatusPausedShouldCallRepository(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('getByPlayingStatusWithoutRecentPlaytime')
            ->with($user->getSteamId());

        $service = new UpdateGameStatusForUserService($repositoryMock);
        $service->setStatusPausedForPlayingGamesWithoutRecentPlayed($user);
    }

    public function testSetStatusPausedShouldSaveNewStatus(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $achievement = new Achievement($user->getSteamId(), $game);
        $playtime = new Playtime($user->getSteamId(), $game);

        $currentGameStats = new GameStats($user->getSteamId(), $game, $achievement, $playtime);
        $currentGameStats->setStatusPlaying();

        $expectedGameStats = new GameStats($user->getSteamId(), $game, $achievement, $playtime);
        $expectedGameStats->setStatusPaused();

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('getByPlayingStatusWithoutRecentPlaytime')
            ->with($user->getSteamId())
            ->willReturn([$currentGameStats]);

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedGameStats);

        $service = new UpdateGameStatusForUserService($repositoryMock);
        $service->setStatusPausedForPlayingGamesWithoutRecentPlayed($user);
    }
}
