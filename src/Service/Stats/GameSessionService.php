<?php

namespace App\Service\Stats;

use App\Repository\GameSessionRepository;
use App\Repository\OverallGameStatsRepository;

/**
 * Class GameSessionsPerMonthService
 */
class GameSessionService extends AbstractStatsService
{
    /**
     * @var GameSessionRepository
     */
    private $sessionRepository;

    /**
     * @var OverallGameStatsRepository
     */
    private $overallGameStatsRepository;

    /**
     * GameSessionService constructor.
     * @param GameSessionRepository $sessionRepository
     * @param OverallGameStatsRepository $overallGameStatsRepository
     */
    public function __construct(
        GameSessionRepository $sessionRepository,
        OverallGameStatsRepository $overallGameStatsRepository
    ) {
        parent::__construct($overallGameStatsRepository);
        $this->sessionRepository = $sessionRepository;
        $this->overallGameStatsRepository = $overallGameStatsRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function recalculate(): string
    {
        $sessions = $this->sessionRepository->findAll();
        $overallGameStats = $this->getOverallGameStats();

        $overallGameStats->setGameSessions(count($sessions));
        $this->overallGameStatsRepository->save($overallGameStats);

        return 'U';
    }
}
