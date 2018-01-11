<?php

namespace App\Service\Steam;

use App\Entity\Game;

/**
 * Class RecentlyPlayedService
 */
class RecentlyPlayedService
{
    public function sortRecentlyPlayedByLastSession(array $recentlyPlayedGames)
    {
        usort($recentlyPlayedGames, function (Game $a, Game $b){
            if ($a->getLastGameSession() === null || $b->getLastGameSession() === null){
                return 1;
            }

            return $a->getLastGameSession()->getCreatedAt() > $b->getLastGameSession()->getCreatedAt() ? -1: 1;
        });

        return $recentlyPlayedGames;
    }
}
