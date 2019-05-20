<?php

namespace App\Service\GameStats;

use App\Entity\User;
use App\Repository\GameStatsRepository;

class UpdateGameStatusForUserService
{
    /**
     * @var GameStatsRepository
     */
    private $gameStatsRepository;

    /**
     * UpdateGameStatusForUserService constructor.
     * @param GameStatsRepository $gameStatsRepository
     */
    public function __construct(GameStatsRepository $gameStatsRepository)
    {
        $this->gameStatsRepository = $gameStatsRepository;
    }

    /**
     * @param User $user
     */
    public function setStatusPlayingForRecentPlayed(User $user): void
    {
        $gameStats = $this->gameStatsRepository->getByRecentlyPlayed($user->getSteamId());

        foreach ($gameStats as $gameStat) {
            $gameStat->setStatusPlaying();
            $this->gameStatsRepository->save($gameStat);
        }
    }

    /**
     * @param User $user
     */
    public function setStatusPausedForPlayingGamesWithoutRecentPlayed(User $user): void
    {
        $gameStats = $this->gameStatsRepository->getByPlayingStatusWithoutRecentPlaytime($user->getSteamId());
        foreach ($gameStats as $gameStat) {
            $gameStat->setStatusPaused();
            $this->gameStatsRepository->save($gameStat);
        }
    }
}
