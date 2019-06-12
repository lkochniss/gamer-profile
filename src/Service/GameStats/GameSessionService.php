<?php

namespace App\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameSession;
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

    /**
     * @param GameSession $gameSession
     * @param int $oldTime
     * @param int $newTime
     */
    public function updateGameSession(GameSession $gameSession, int $oldTime, int $newTime): void
    {
        $diff = $newTime - $oldTime;

        if ($diff > 70) {
            echo sprintf(
                'Something is wrong with the session for %s. The diff was %s based on old duration %s and new %s',
                $gameSession->getGame()->getName(),
                $diff,
                $oldTime,
                $newTime
            );
            
            return;
        }


        if ($newTime > $oldTime) {
            $gameSession->addDuration($diff);

            $this->gameSessionRepository->save($gameSession);
        }
    }
}
