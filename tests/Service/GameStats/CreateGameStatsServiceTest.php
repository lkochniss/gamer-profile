<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\Playtime;
use App\Entity\User;
use App\Repository\GameStatsRepository;
use App\Service\GameStats\AchievementService;
use App\Service\GameStats\CreateGameStatsService;
use App\Service\GameStats\PlaytimeService;
use PHPUnit\Framework\TestCase;

class CreateGameStatsServiceTest extends TestCase
{
    public function testExecuteShouldCallRepository(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $user->getSteamId(),
                'game' => $game
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $playtimeServiceMock = $this->createMock(PlaytimeService::class);

        $service = new CreateGameStatsService(
            $achievementServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );

        $service->execute($user, $game);
    }

    public function testExecuteShouldCallSkipOnExistingGameStats(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $user->getSteamId(),
                'game' => $game
            ])
            ->willReturn(new GameStats(
                $user->getSteamId(),
                $game,
                new Achievement($user->getSteamId(), $game),
                new Playtime($user->getSteamId(), $game)
            ));

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->never())
            ->method('create');

        $playtimeServiceMock = $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->never())
            ->method('create');

        $service = new CreateGameStatsService(
            $achievementServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );

        $service->execute($user, $game);
    }

    public function testExecuteShouldCallAchievementService(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $user->getSteamId(),
                'game' => $game
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->once())
            ->method('create')
            ->with(
                $user,
                $game
            );

        $playtimeServiceMock = $this->createMock(PlaytimeService::class);

        $service = new CreateGameStatsService(
            $achievementServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );

        $service->execute($user, $game);
    }

    public function testExecuteShouldCallPlaytimeService(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $user->getSteamId(),
                'game' => $game
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);

        $playtimeServiceMock = $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('create')
            ->with(
                $user,
                $game
            );

        $service = new CreateGameStatsService(
            $achievementServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );

        $service->execute($user, $game);
    }

    public function testExecuteShouldSaveGameStats(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $achievement = new Achievement($user->getSteamId(), $game);
        $playtime = new Playtime($user->getSteamId(), $game);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $user->getSteamId(),
                'game' => $game
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->once())
            ->method('create')
            ->willReturn($achievement);

        $playtimeServiceMock = $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('create')
            ->willReturn($playtime);

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with(new GameStats(
                $user->getSteamId(),
                $game,
                $achievement,
                $playtime
            ));

        $service = new CreateGameStatsService(
            $achievementServiceMock,
            $playtimeServiceMock,
            $repositoryMock
        );

        $service->execute($user, $game);
    }
}
