<?php

namespace App\Service\Stats;

use App\Entity\OverallGameStats;
use App\Entity\User;
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
     * @param User $user
     * @return OverallGameStats
     */
    protected function getOverallGameStats(User $user): OverallGameStats
    {
        $overallGameStats = $this->overallGameStatsRepository->findOneBy(['user' => $user]);

        if (is_null($overallGameStats)) {
            $overallGameStats = new OverallGameStats($user);
        }

        return $overallGameStats;
    }
}
