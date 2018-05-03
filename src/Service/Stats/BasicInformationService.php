<?php

namespace App\Service\Stats;

use App\Entity\BasicInformationChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Repository\OverallGameStatsRepository;

/**
 * Class BasicInformationService
 */
class BasicInformationService extends AbstractStatsService
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
     * @param Game $game
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addGameInformation(Game $game): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        $overallGameStats->addToOverallAchievements($game->getOverallAchievements());
        $overallGameStats->addToPlayerAchievements($game->getPlayerAchievements());
        $overallGameStats->addToRecentlyPlayed($game->getRecentlyPlayed());
        $overallGameStats->addToTimePlayed($game->getTimePlayed());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }

    /**
     * @param BasicInformationChangeSet $basicInformationChangeSet
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateChangeSet(BasicInformationChangeSet $basicInformationChangeSet): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        $overallGameStats->addToOverallAchievements($basicInformationChangeSet->getOverallAchievements());
        $overallGameStats->addToPlayerAchievements($basicInformationChangeSet->getPlayerAchievements());
        $overallGameStats->addToRecentlyPlayed($basicInformationChangeSet->getRecentlyPlayed());
        $overallGameStats->addToTimePlayed($basicInformationChangeSet->getTimePlayed());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }
}
