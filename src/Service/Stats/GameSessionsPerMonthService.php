<?php

namespace App\Service\Stats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\OverallGameStats;
use App\Entity\User;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;

/**
 * Class GameSessionsPerMonthService
 */
class GameSessionsPerMonthService extends AbstractStatsService
{
    /**
     * @var GameSessionsPerMonthRepository
     */
    private $gameSessionsPerMonthRepository;

    /**
     * @var OverallGameStatsRepository
     */
    private $overallGameStatsRepository;

    /**
     * GameSessionsPerMonthService constructor.
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param OverallGameStatsRepository $overallGameStatsRepository
     */
    public function __construct(
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        OverallGameStatsRepository $overallGameStatsRepository
    ) {
        parent::__construct($overallGameStatsRepository);
        $this->gameSessionsPerMonthRepository = $gameSessionsPerMonthRepository;
        $this->overallGameStatsRepository = $overallGameStatsRepository;
    }

    /**
     * @param User $user
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addSessionToOverallGameStats(User $user): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($user);
        $overallGameStats->addGameSessions();

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }

    /**
     * @param GameSession $gameSession
     * @return GameSessionsPerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addGameSession(GameSession $gameSession): GameSessionsPerMonth
    {
        $gameSessionPerMonth = $this->getGameSessionsPerMonth($gameSession->getGame(), $gameSession->getUser());
        $gameSessionPerMonth->addToDuration($gameSession->getDuration());

        $this->gameSessionsPerMonthRepository->save($gameSessionPerMonth);

        return $gameSessionPerMonth;
    }

    /**
     * @param int $diff
     * @param GameSession $gameSession
     * @return GameSessionsPerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateGameSession(int $diff, GameSession $gameSession): GameSessionsPerMonth
    {
        $gameSessionPerMonth = $this->getGameSessionsPerMonth($gameSession->getGame(), $gameSession->getUser());
        $gameSessionPerMonth->addToDuration($diff);

        $this->gameSessionsPerMonthRepository->save($gameSessionPerMonth);

        return $gameSessionPerMonth;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return GameSessionsPerMonth
     */
    private function getGameSessionsPerMonth(Game $game, User $user): GameSessionsPerMonth
    {
        $month = new \DateTime('first day of this month 00:00:00');
        $gameSessionsPerMonth = $this->gameSessionsPerMonthRepository->findOneBy([
            'month' => $month,
            'game' => $game
        ]);

        if (is_null($gameSessionsPerMonth)) {
            $gameSessionsPerMonth = new GameSessionsPerMonth($month, $game, $user);
        }

        return $gameSessionsPerMonth;
    }
}
