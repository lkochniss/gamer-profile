<?php

namespace App\Service\Entity;

use App\Entity\GameSession;
use App\Entity\JSON\JsonPlaytime;
use App\Entity\Playtime;
use App\Repository\GameSessionRepository;
use App\Repository\GameStatsRepository;

/**
 * Class SessionService
 */
class SessionService
{
    /**
     * @var GameStatsRepository
     */
    private $gameStatsRepository;

    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * SessionService constructor.
     * @param GameStatsRepository $gameStatsRepository
     * @param GameSessionRepository $gameSessionRepository
     */
    public function __construct(GameStatsRepository $gameStatsRepository, GameSessionRepository $gameSessionRepository)
    {
        $this->gameStatsRepository = $gameStatsRepository;
        $this->gameSessionRepository = $gameSessionRepository;
    }

    /**
     * @param Playtime $playtime
     * @param JsonPlaytime $jsonPlaytime
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrUpdate(Playtime $playtime, JsonPlaytime $jsonPlaytime): string
    {
        $date = new \DateTime('today 00:00:00');
        $session = $this->gameSessionRepository->findOneBy(
            ['game' => $playtime->getGame(),
                'user' => $playtime->getUser(),
                'date' => $date]
        );

        $gameStats = $this->gameStatsRepository->findOneBy([
            'game' => $playtime->getGame(),
            'user' => $playtime->getUser()
        ]);

        if (is_null($session)) {
            $session = new GameSession($playtime->getGame(), $playtime->getUser(), $date);
            $session->setGameStats($gameStats);
        }

        $diff = $jsonPlaytime->getRecentPlaytime() - $playtime->getRecentPlaytime() - $session->getDuration();
        $session->setDuration($diff);

        $this->gameSessionRepository->save($session);

        return 'U';
    }
}
