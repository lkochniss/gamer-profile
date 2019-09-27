<?php

namespace App\Service\Steam;

use App\Entity\Game;
use App\Repository\GameStatsRepository;
use App\Service\Transformation\GameUserInformationService;

class CompareGamesForUserService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var GameStatsRepository
     */
    private $gameStatsRepository;

    /**
     * CompareGamesForUserService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param GameStatsRepository $gameStatsRepository
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        GameStatsRepository $gameStatsRepository
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->gameStatsRepository = $gameStatsRepository;
    }

    /**
     * @param string $mySteamUserId
     * @param string $friendsSteamUserId
     * @return Game[]
     */
    public function compareMyGamesWithFriend(string $mySteamUserId, string $friendsSteamUserId): array
    {
        $myGameStats = $this->gameStatsRepository->findBy([
            'steamUserId' => $mySteamUserId
        ]);

        $friendsJsonGames = $this->gameUserInformationService->getAllGames($friendsSteamUserId);

        $games = [];
        foreach ($myGameStats as $gameStat) {
            if (array_key_exists($gameStat->getGame()->getSteamAppId(), $friendsJsonGames)) {
                $games[] = $gameStat->getGame();
            }
        }

        return $games;
    }
}
