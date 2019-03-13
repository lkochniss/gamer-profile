<?php

namespace App\Service\Stats;

use App\Entity\User;
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
     * @param User $user
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function recalculate(User $user): string
    {
        $sessions = $this->sessionRepository->findBy(['user' => $user]);

        $overallGameStats = $this->getOverallGameStats($user);

        $overallGameStats->setGameSessions(count($sessions));
        $this->overallGameStatsRepository->save($overallGameStats);

        return 'U';
    }
}
