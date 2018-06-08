<?php

namespace App\Service\Transformation;

use App\Entity\Game;

/**
 * Class RecentlyPlayedGamesService
 *
 * @SuppressWarnings(PHPMD.LongVariableName)
 */
class RecentlyPlayedGamesService
{
    /**
     * @param array $recentlyPlayedGames
     * @return array
     */
    public function sortRecentlyPlayedGamesByLastSession(array $recentlyPlayedGames): array
    {
        usort($recentlyPlayedGames, function (Game $gameA, Game $gameB) {
            if ($gameA->getLastGameSession() === null || $gameB->getLastGameSession() === null) {
                return -1;
            }

            return $gameA->getLastGameSession()->getCreatedAt() > $gameB->getLastGameSession()->getCreatedAt() ? -1: 1;
        });

        return $recentlyPlayedGames;
    }
}
