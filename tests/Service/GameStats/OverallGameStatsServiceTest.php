<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Achievement;
use App\Entity\ChangeSet\AchievementChangeSet;
use App\Entity\ChangeSet\PlaytimeChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Playtime;
use App\Repository\OverallGameStatsRepository;
use App\Service\GameStats\OverallGameStatsService;
use PHPUnit\Framework\TestCase;

class OverallGameStatsServiceTest extends TestCase
{
    public function testAddAchievementShouldGetOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId]);

        $service = new OverallGameStatsService($repositoryMock);
        $service->addAchievement(new Achievement($steamUserId, new Game(2)));
    }

    public function testAddAchievementShouldSaveOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId])
            ->willReturn(new OverallGameStats($steamUserId));

        $achievement = new Achievement($steamUserId, new Game(2));
        $achievement->setPlayerAchievements(2);
        $achievement->setOverallAchievements(5);

        $expectedOverallGameStats = new OverallGameStats($steamUserId);
        $expectedOverallGameStats->addToOverallAchievements($achievement->getOverallAchievements());
        $expectedOverallGameStats->addToPlayerAchievements($achievement->getPlayerAchievements());

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedOverallGameStats);

        $service = new OverallGameStatsService($repositoryMock);

        $service->addAchievement($achievement);
    }

    public function testAddAchievementShouldGenerateOverallGameStatsIfNoneExist(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId]);

        $achievement = new Achievement($steamUserId, new Game(2));
        $achievement->setPlayerAchievements(2);
        $achievement->setOverallAchievements(5);

        $expectedOverallGameStats = new OverallGameStats($steamUserId);
        $expectedOverallGameStats->addToOverallAchievements($achievement->getOverallAchievements());
        $expectedOverallGameStats->addToPlayerAchievements($achievement->getPlayerAchievements());

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedOverallGameStats);

        $service = new OverallGameStatsService($repositoryMock);

        $service->addAchievement($achievement);
    }

    public function testAddSessionShouldGetOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId]);

        $service = new OverallGameStatsService($repositoryMock);
        $service->addSession($steamUserId);
    }

    public function testAddSessionShouldSaveOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId])
            ->willReturn(new OverallGameStats($steamUserId));
        $expectedOverallGameStats = new OverallGameStats($steamUserId);
        $expectedOverallGameStats->addGameSessions();

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedOverallGameStats);

        $service = new OverallGameStatsService($repositoryMock);

        $service->addSession($steamUserId);
    }

    public function testAddPlaytimeShouldGetOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId]);

        $service = new OverallGameStatsService($repositoryMock);
        $service->addPlaytime(new Playtime($steamUserId, new Game(2)));
    }

    public function testAddPlaytimeShouldSaveOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId])
            ->willReturn(new OverallGameStats($steamUserId));

        $playtime = new Playtime($steamUserId, new Game(2));
        $playtime->setOverallPlaytime(20);
        $playtime->setRecentPlaytime(10);

        $expectedOverallGameStats = new OverallGameStats($steamUserId);
        $expectedOverallGameStats->addToOverallPlaytime($playtime->getOverallPlaytime());
        $expectedOverallGameStats->addToRecentPlaytime($playtime->getRecentPlaytime());

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedOverallGameStats);

        $service = new OverallGameStatsService($repositoryMock);
        $service->addPlaytime($playtime);
    }

    public function testUpdatePlaytimeWithChangeSetShouldGetOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId]);

        $service = new OverallGameStatsService($repositoryMock);

        $changeSet = new PlaytimeChangeSet();
        $changeSet->setSteamUserId($steamUserId);
        $service->updatePlaytimeWithChangeSet($changeSet);
    }

    public function testUpdatePlaytimeWithChangeSetShouldSaveOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId])
            ->willReturn(new OverallGameStats($steamUserId));

        $changeSet = new PlaytimeChangeSet();
        $changeSet->setOverallPlaytime(20);
        $changeSet->setRecentPlaytime(10);
        $changeSet->setSteamUserId($steamUserId);

        $expectedOverallGameStats = new OverallGameStats($steamUserId);
        $expectedOverallGameStats->addToOverallPlaytime($changeSet->getOverallPlaytime());
        $expectedOverallGameStats->addToRecentPlaytime($changeSet->getRecentPlaytime());

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedOverallGameStats);

        $service = new OverallGameStatsService($repositoryMock);
        $service->updatePlaytimeWithChangeSet($changeSet);
    }

    public function testUpdateAchievementWithChangeSetShouldGetOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId]);

        $service = new OverallGameStatsService($repositoryMock);

        $changeSet = new AchievementChangeSet();
        $changeSet->setSteamUserId($steamUserId);
        $service->updateAchievementWithChangeSet($changeSet);
    }

    public function testUpdateAchievementWithChangeSetShouldSaveOverallGameStats(): void
    {
        $steamUserId = 1;
        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['steamUserId' => $steamUserId])
            ->willReturn(new OverallGameStats($steamUserId));

        $changeSet = new AchievementChangeSet();
        $changeSet->setOverallAchievements(20);
        $changeSet->setPlayerAchievements(10);
        $changeSet->setSteamUserId($steamUserId);

        $expectedOverallGameStats = new OverallGameStats($steamUserId);
        $expectedOverallGameStats->addToOverallAchievements($changeSet->getOverallAchievements());
        $expectedOverallGameStats->addToPlayerAchievements($changeSet->getPlayerAchievements());

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedOverallGameStats);

        $service = new OverallGameStatsService($repositoryMock);
        $service->updateAchievementWithChangeSet($changeSet);
    }
}
