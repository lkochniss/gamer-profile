<?php

namespace tests\App\Service\Stats;

use App\Entity\Achievement;
use App\Entity\ChangeSet\AchievementChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\User;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\AchievementService;
use PHPUnit\Framework\TestCase;

/**
 * Class AchievementServiceTest
 */
class AchievementServiceTest extends TestCase
{
    public function testAddGameInformation(): void
    {
        $user = new User(1);
        $expectedOverallGameStats = new OverallGameStats($user);
        $expectedOverallGameStats->addToOverallAchievements(5);
        $expectedOverallGameStats->addToPlayerAchievements(1);

        $game = new Game(1);

        $achievement = new Achievement($user, $game);
        $achievement->setOverallAchievements(5);
        $achievement->setPlayerAchievements(1);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user])
            ->willReturn(new OverallGameStats($user));

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $achievementService = new AchievementService($overallGameStatsRepositoryMock);

        $this->assertEquals(
            $expectedOverallGameStats,
            $achievementService->addNew($achievement)
        );
    }

    public function testUpdateChangeSet(): void
    {
        $user = new User(1);
        $expectedOverallGameStats = new OverallGameStats($user);
        $expectedOverallGameStats->addToOverallAchievements(5);
        $expectedOverallGameStats->addToPlayerAchievements(1);

        $achievementChangeSet = new AchievementChangeSet();
        $achievementChangeSet->setOverallAchievements(5);
        $achievementChangeSet->setPlayerAchievements(1);
        $achievementChangeSet->setUser($user);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user])
            ->willReturn(new OverallGameStats($user));

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $achievementService = new AchievementService($overallGameStatsRepositoryMock);

        $this->assertEquals(
            $expectedOverallGameStats,
            $achievementService->updateChangeSet($achievementChangeSet)
        );
    }
}
