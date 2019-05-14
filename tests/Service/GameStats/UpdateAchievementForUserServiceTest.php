<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Service\GameStats\AchievementService;
use App\Service\GameStats\UpdateAchievementForUserService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class UpdateAchievementForUserServiceTest extends TestCase
{
    public function testRecentlyShouldCallUserInformationService(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId());

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $repositoryMock = $this->createMock(GameRepository::class);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->recently($user);
    }

    public function testRecentlyShouldGetGameEntityForRecentlyPlayed(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => 2
                ]
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->recently($user);
    }

    public function testRecentlyShouldSkipOnMissingGameEntity(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => 2
                ]
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->never())
            ->method('updateGameForUserIfNoneExisting');

        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2)
            ->willReturn(null);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->recently($user);
    }

    public function testRecentlyShouldCallAchievementService(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => $game->getSteamAppId()
                ]
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->once())
            ->method('updateGameForUser')
            ->with(
                $game,
                $user
            );

        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2)
            ->willReturn($game);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->recently($user);
    }

    public function testNoneExistingShouldCallUserInformationService(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId());

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $repositoryMock = $this->createMock(GameRepository::class);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->noneExisting($user);
    }

    public function testNoneExistingShouldGetGameEntityForRecentlyPlayed(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => 2
                ]
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->noneExisting($user);
    }

    public function testNoneExistingShouldSkipOnMissingGameEntity(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => 2
                ]
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->never())
            ->method('updateGameForUserIfNoneExisting');

        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2)
            ->willReturn(null);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->noneExisting($user);
    }

    public function testNoneExistingShouldCallAchievementService(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);

        $informationServiceMock =  $this->createMock(GameUserInformationService::class);
        $informationServiceMock->expects($this->once())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn([
                [
                    'appid' => $game->getSteamAppId()
                ]
            ]);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->once())
            ->method('updateGameForUserIfNoneExisting')
            ->with(
                $game,
                $user
            );

        $repositoryMock = $this->createMock(GameRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBySteamAppId')
            ->with(2)
            ->willReturn($game);

        $service = new UpdateAchievementForUserService(
            $informationServiceMock,
            $achievementServiceMock,
            $repositoryMock
        );

        $service->noneExisting($user);
    }
}
