<?php

namespace App\Twig;

use App\Entity\GameSession;
use App\Entity\GameStats;
use App\Repository\GameSessionRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class GameSessionExtension
 */
class GameSessionExtension extends AbstractExtension
{
    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * GameSessionExtension constructor.
     * @param GameSessionRepository $gameSessionRepository
     */
    public function __construct(GameSessionRepository $gameSessionRepository)
    {
        $this->gameSessionRepository = $gameSessionRepository;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('get_last_game_session', [$this, 'getLastGameSession']),
            new TwigFilter('get_number_of_sessions', [$this, 'getNumberOfSessions']),
        ];
    }

    /**
     * @param GameStats $gameStats
     * @return GameSession
     */
    public function getLastGameSession(GameStats $gameStats): ?GameSession
    {
        $gameSessions = $this->gameSessionRepository->findBy([
            'game' => $gameStats->getGame(),
            'user' => $gameStats->getUser()
        ]);

        return end($gameSessions)?: null;
    }


    /**
     * @param GameStats $gameStats
     * @return int
     */
    public function getNumberOfSessions(GameStats $gameStats): int
    {
        $gameSessions = $this->gameSessionRepository->findBy([
            'game' => $gameStats->getGame(),
            'user' => $gameStats->getUser()
        ]);

        return count($gameSessions);
    }
}
