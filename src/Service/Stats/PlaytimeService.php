<?php

namespace App\Service\Stats;

use App\Entity\Playtime;
use App\Entity\ChangeSet\PlaytimeChangeSet;
use App\Entity\OverallGameStats;
use App\Repository\OverallGameStatsRepository;

/**
 * Class PlaytimeService
 */
class PlaytimeService extends AbstractStatsService
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
     * @param Playtime $playtime
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNew(Playtime $playtime): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($playtime->getUser());

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
    public function updateChangeSet(PlaytimeChangeSet $playtimeChangeSet): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($playtimeChangeSet->getUser());

        $overallGameStats->addToRecentPlaytime($playtimeChangeSet->getRecentPlaytime());
        $overallGameStats->addToOverallPlaytime($playtimeChangeSet->getOverallPlaytime());

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }
}
