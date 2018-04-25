<?php

namespace App\Service;

use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Repository\GameSessionRepository;
use App\Repository\GameSessionsPerMonthRepository;

/**
 * Class GameSessionsPerMonthService
 */
class GameSessionsPerMonthService
{
    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * @var GameSessionsPerMonthRepository
     */
    private $gameSessionsPerMonthRepository;

    /**
     * GameSessionsPerMonthService constructor.
     * @param GameSessionRepository $gameSessionRepository
     * @param GameSessionsPerMonthRepository $gameSessionPerMonthRepository
     */
    public function __construct(
        GameSessionRepository $gameSessionRepository,
        GameSessionsPerMonthRepository $gameSessionPerMonthRepository
    ) {
        $this->gameSessionRepository = $gameSessionRepository;
        $this->gameSessionsPerMonthRepository = $gameSessionPerMonthRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateGameSessionsPerMonth(): void
    {
        $gameSessions = $this->gameSessionRepository->findAll();

        $gameSessionsPerMonth = $this->gameSessionsPerMonthRepository->findAll();
        if (!empty($gameSessionsPerMonth)) {
            return;
        }

        foreach ($gameSessions as $gameSession) {
            $gameWithSessions = $this->getGameSessionsPerMonth($gameSession);
            $gameWithSessions->addToDuration($gameSession->getDuration());
            $this->gameSessionsPerMonthRepository->save($gameWithSessions);
        }
    }

    /**
     * @param GameSession $gameSession
     * @return GameSessionsPerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getGameSessionsPerMonth(
        GameSession $gameSession
    ): GameSessionsPerMonth {
        $month = new \DateTime(sprintf('first day of %s 00:00:00', $gameSession->getCreatedAt()->format('F Y')));
        $gameSessionsPerMonth = $this->gameSessionsPerMonthRepository->findOneBy([
            'game' => $gameSession->getGame(),
            'month' => $month
        ]);

        if (is_null($gameSessionsPerMonth)) {
            $gameSessionsPerMonth = new GameSessionsPerMonth($month, $gameSession->getGame());
            $this->gameSessionsPerMonthRepository->save($gameSessionsPerMonth);
        }

        return $gameSessionsPerMonth;
    }
}
