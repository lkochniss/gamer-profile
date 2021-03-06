<?php

namespace App\Service\GameStats;

use App\Entity\Achievement;
use App\Entity\ChangeSet\AchievementChangeSet;
use App\Entity\ChangeSet\PlaytimeChangeSet;
use App\Entity\OverallGameStats;
use App\Entity\Playtime;
use App\Repository\OverallGameStatsRepository;

/**
 * Class OverallGameStatsService
 */
class OverallGameStatsService
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
        $this->overallGameStatsRepository = $overallGameStatsRepository;
    }

    /**
     * @param Achievement $achievement
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAchievement(Achievement $achievement): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($achievement->getSteamUserId());

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
    public function updateAchievementWithChangeSet(AchievementChangeSet $achievementChangeSet): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($achievementChangeSet->getSteamUserId());

        $overallGameStats->addToOverallAchievements($achievementChangeSet->getOverallAchievements());
        $overallGameStats->addToPlayerAchievements($achievementChangeSet->getPlayerAchievements());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }

    /**
     * @param int $steamUserId
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addSession(int $steamUserId): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($steamUserId);
        $overallGameStats->addGameSessions();

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }

    /**
     * @param Playtime $playtime
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addPlaytime(Playtime $playtime): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($playtime->getSteamUserId());

        $overallGameStats->addToRecentPlaytime($playtime->getRecentPlaytime());
        $overallGameStats->addToOverallPlaytime($playtime->getOverallPlaytime());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }

    /**
     * @param PlaytimeChangeSet $playtimeChangeSet
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePlaytimeWithChangeSet(PlaytimeChangeSet $playtimeChangeSet): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($playtimeChangeSet->getSteamUserId());

        $overallGameStats->addToRecentPlaytime($playtimeChangeSet->getRecentPlaytime());
        $overallGameStats->addToOverallPlaytime($playtimeChangeSet->getOverallPlaytime());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }


    /**
     * @param int $steamUserId
     * @return OverallGameStats
     */
    private function getOverallGameStats(int $steamUserId): OverallGameStats
    {
        $overallGameStats = $this->overallGameStatsRepository->findOneBy(['steamUserId' => $steamUserId]);

        if (is_null($overallGameStats)) {
            $overallGameStats = new OverallGameStats($steamUserId);
        }

        return $overallGameStats;
    }
}
