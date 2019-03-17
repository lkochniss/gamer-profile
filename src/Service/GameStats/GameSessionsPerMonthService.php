<?php

namespace App\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\User;
use App\Repository\GameSessionsPerMonthRepository;

/**
 * Class GameSessionsPerMonthService
 */
class GameSessionsPerMonthService
{
    /**
     * @var GameSessionsPerMonthRepository
     */
    private $gameSessionsPerMonthRepository;

    /**
     * GameSessionsPerMonthService constructor.
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     */
    public function __construct(GameSessionsPerMonthRepository $gameSessionsPerMonthRepository)
    {
        $this->gameSessionsPerMonthRepository = $gameSessionsPerMonthRepository;
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
            'game' => $game,
            'user' => $user,
        ]);

        if (is_null($gameSessionsPerMonth)) {
            $gameSessionsPerMonth = new GameSessionsPerMonth($month, $game, $user);
        }

        return $gameSessionsPerMonth;
    }
}