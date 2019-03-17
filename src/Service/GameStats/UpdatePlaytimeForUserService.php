<?php

namespace App\Service\GameStats;

use App\Entity\User;
use App\Repository\GameRepository;
use App\Service\Transformation\GameUserInformationService;

class UpdatePlaytimeForUserService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var PlaytimeService
     */
    private $playtimeService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdatePlaytimeForUserService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param PlaytimeService $playtimeService
     * @param GameRepository $gameRepository
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        PlaytimeService $playtimeService,
        GameRepository $gameRepository
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->playtimeService = $playtimeService;
        $this->gameRepository = $gameRepository;
    }


    /**
     * @param User $user
     */
    public function execute(User $user): void
    {
        $this->playtimeService->resetRecentPlaytimeForUser($user);
        $recentlyPlayedGames = $this->gameUserInformationService->getRecentlyPlayedGames($user->getSteamId());

        foreach ($recentlyPlayedGames as $gameArray) {
            $game  = $this->gameRepository->findOneBySteamAppId($gameArray['appid']);
            if (!is_null($game)) {
                $this->playtimeService->updateGameForUser($game, $user);
            }
        }
    }
}
