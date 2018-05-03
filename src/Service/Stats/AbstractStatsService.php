<?php

namespace App\Service\Stats;

use App\Entity\OverallGameStats;
use App\Repository\OverallGameStatsRepository;

/**
 * Class AbstractStatsService
 */
abstract class AbstractStatsService
{
    /**
     * @var OverallGameStatsRepository
     */
    private $overallGameStatsRepository;

    /**
     * AbstractStatsService constructor.
     * @param OverallGameStatsRepository $overallGameStatsRepository
     */
    public function __construct(OverallGameStatsRepository $overallGameStatsRepository)
    {
        $this->overallGameStatsRepository = $overallGameStatsRepository;
    }

    /**
     * @return OverallGameStats
     */
    protected function getOverallGameStats(): OverallGameStats
    {
        $overallGameStats = $this->overallGameStatsRepository->findOneBy(['identifier' => getenv('STEAM_USER_ID')]);

        if (is_null($overallGameStats)) {
            $overallGameStats = new OverallGameStats();
        }

        return $overallGameStats;
    }
}
