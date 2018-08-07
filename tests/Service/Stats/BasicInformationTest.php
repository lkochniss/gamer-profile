<?php

namespace tests\App\Service\Stats;

use App\Entity\PlaytimeChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\PlaytimeService;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicInformationTest
 */
class BasicInformationTest extends TestCase
{
    public function testAddGameInformation(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToOverallAchievements(5);
        $expectedOverallGameStats->addToPlayerAchievements(1);
        $expectedOverallGameStats->addToRecentlyPlayed(10);
        $expectedOverallGameStats->addToTimePlayed(15);

        $game = new Game();
        $game->setOverallAchievements(5);
        $game->setPlayerAchievements(1);
        $game->setRecentlyPlayed(10);
        $game->setTimePlayed(15);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $basicInformationService = new PlaytimeService($overallGameStatsRepositoryMock);

        $this->assertEquals(
            $expectedOverallGameStats,
            $basicInformationService->addGameInformation($game)
        );
    }

    public function testUpdateChangeSet(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToOverallAchievements(5);
        $expectedOverallGameStats->addToPlayerAchievements(1);
        $expectedOverallGameStats->addToRecentlyPlayed(10);
        $expectedOverallGameStats->addToTimePlayed(15);

        $basicInformationChangeSet = new PlaytimeChangeSet();
        $basicInformationChangeSet->setOverallAchievements(5);
        $basicInformationChangeSet->setPlayerAchievements(1);
        $basicInformationChangeSet->setRecentlyPlayed(10);
        $basicInformationChangeSet->setTimePlayed(15);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $basicInformationService = new PlaytimeService($overallGameStatsRepositoryMock);

        $this->assertEquals(
            $expectedOverallGameStats,
            $basicInformationService->updateChangeSet($basicInformationChangeSet)
        );
    }
}
