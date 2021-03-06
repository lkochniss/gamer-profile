<?php

namespace App\Service\Steam;

use App\Service\Transformation\GameUserInformationService;

class GamesForUserService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * GamesForUserService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param GameService $gameService
     */
    public function __construct(GameUserInformationService $gameUserInformationService, GameService $gameService)
    {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->gameService = $gameService;
    }

    /**
     * @param string $steamUserId
     */
    public function create(string $steamUserId): void
    {
        $games = $this->gameUserInformationService->getAllGames($steamUserId);

        if (!empty($games)) {
            foreach ($games as $gameArray) {
                $this->gameService->create($gameArray['appid']);
            }
        }
    }

    /**
     * @param string $steamUserId
     */
    public function updateRecentlyPlayed(string $steamUserId): void
    {
        $games = $this->gameUserInformationService->getRecentlyPlayedGames($steamUserId);

        if (!empty($games)) {
            foreach ($games as $gameArray) {
                $this->gameService->updateGameBySteamAppId($gameArray['appid']);
            }
        }
    }
}
