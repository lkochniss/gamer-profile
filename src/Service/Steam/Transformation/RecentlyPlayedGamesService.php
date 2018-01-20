<?php

namespace App\Service\Steam\Transformation;

use App\Entity\Game;

/**
 * Class RecentlyPlayedGamesService
 */
class RecentlyPlayedGamesService
{
    /**
     * @param array $recentlyPlayedGames
     * @return array
     */
    public function sortRecentlyPlayedGamesByLastSession(array $recentlyPlayedGames): array
    {
        usort($recentlyPlayedGames, function (Game $a, Game $b) {
            if ($a->getLastGameSession() === null || $b->getLastGameSession() === null) {
                return -1;
            }

            return $a->getLastGameSession()->getCreatedAt() > $b->getLastGameSession()->getCreatedAt() ? -1: 1;
        });

        return $recentlyPlayedGames;
    }
}
