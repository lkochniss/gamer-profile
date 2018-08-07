<?php

namespace App\Service\Stats;

use App\Entity\Achievement;
use App\Entity\ChangeSet\AchievementChangeSet;
use App\Entity\OverallGameStats;
use App\Repository\OverallGameStatsRepository;

/**
 * Class AchievementService
 */
class AchievementService extends AbstractStatsService
{
    /**
     * @var OverallGameStatsRepository
     */
    private $overallGameStatsRepository;

    /**
     * BasicInformationService constructor.
     * @param OverallGameStatsRepository $overallGameStatsRepository
     */
    public function __construct(OverallGameStatsRepository $overallGameStatsRepository)
    {
        parent::__construct($overallGameStatsRepository);
        $this->overallGameStatsRepository = $overallGameStatsRepository;
    }

    /**
     * @param Achievement $achievement
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNew(Achievement $achievement): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($achievement->getUser());

        $overallGameStats->addToOverallAchievements($achievement->getOverallAchievements());
        $overallGameStats->addToPlayerAchievements($achievement->getPlayerAchievements());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }

    /**
     * @param AchievementChangeSet $achievementChangeSet
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateChangeSet(AchievementChangeSet $achievementChangeSet): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($achievementChangeSet->getUser());

        $overallGameStats->addToOverallAchievements($achievementChangeSet->getOverallAchievements());
        $overallGameStats->addToPlayerAchievements($achievementChangeSet->getPlayerAchievements());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }
}
