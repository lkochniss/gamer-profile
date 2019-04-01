<?php

namespace App\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\User;
use App\Repository\GameSessionRepository;

class GameSessionService
{
    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * GameSessionService constructor.
     * @param GameSessionRepository $gameSessionRepository
     */
    public function __construct(GameSessionRepository $gameSessionRepository)
    {
        $this->gameSessionRepository = $gameSessionRepository;
    }

    /**
     * @param string $steamUserId
     * @param Game $game
     * @return GameSession
     */
    public function getTodaysGameSession(string $steamUserId, Game $game): GameSession
    {
        $today = new \DateTime('today 00:00:00');
        $gameSession = $this->gameSessionRepository->findOneBy([
            'steamUserId' => $steamUserId,
            'game' => $game,
            'date' => $today
        ]);

        if (!is_null($gameSession)) {
            return $gameSession;
        }

        $gameSession = new GameSession($game, $steamUserId);

        return $gameSession;
    }

    public function updateGameSession(GameSession $gameSession, int $oldTime, int $newTime): void
    {
        if ($newTime > $oldTime) {
            $diff = $newTime - $oldTime;
            $gameSession->addDuration($diff);

            try {
                $this->gameSessionRepository->save($gameSession);
            } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
            } catch (\Doctrine\ORM\ORMException $exception) {
            }
        }
    }
}
