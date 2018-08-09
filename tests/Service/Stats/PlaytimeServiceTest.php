<?php

namespace tests\App\Service\Stats;

use App\Entity\ChangeSet\PlaytimeChangeSet;
use App\Entity\Playtime;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\User;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\PlaytimeService;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaytimeServiceTest
 */
class PlaytimeServiceTest extends TestCase
{
    public function testAddGameInformation(): void
    {
        $user = new User(1);
        $expectedOverallGameStats = new OverallGameStats($user);
        $expectedOverallGameStats->addToRecentPlaytime(10);
        $expectedOverallGameStats->addToOverallPlaytime(15);

        $game = new Game();
        $playtime = new Playtime($user, $game);
        $playtime->setRecentPlaytime(10);
        $playtime->setOverallPlaytime(15);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user])
            ->willReturn(new OverallGameStats($user));

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $playtimeService = new PlaytimeService($overallGameStatsRepositoryMock);

        $this->assertEquals(
            $expectedOverallGameStats,
            $playtimeService->addNew($playtime)
        );
    }

    public function testUpdateChangeSet(): void
    {
        $user = new User(1);
        $expectedOverallGameStats = new OverallGameStats($user);
        $expectedOverallGameStats->addToRecentPlaytime(10);
        $expectedOverallGameStats->addToOverallPlaytime(15);

        $playtimeChangeSet = new PlaytimeChangeSet();
        $playtimeChangeSet->setRecentPlaytime(10);
        $playtimeChangeSet->setOverallPlaytime(15);
        $playtimeChangeSet->setUser($user);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user])
            ->willReturn(new OverallGameStats($user));

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $basicInformationService = new PlaytimeService($overallGameStatsRepositoryMock);

        $this->assertEquals(
            $expectedOverallGameStats,
            $basicInformationService->updateChangeSet($playtimeChangeSet)
        );
    }
}
