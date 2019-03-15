<?php

namespace App\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameStatsRepository;
use App\Service\Transformation\GameUserInformationService;

class CreateGameStatsForUsersGamesService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var CreateGameStatsService
     */
    private $createGameStatsService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * CreateGameStatsForUsersGamesService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param CreateGameStatsService $createGameStatsService
     * @param GameRepository $gameRepository
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        CreateGameStatsService $createGameStatsService,
        GameRepository $gameRepository
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->createGameStatsService = $createGameStatsService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param User $user
     */
    public function execute(User $user)
    {
        $games = $this->gameUserInformationService->getAllGames($user->getSteamId());

        if (!empty($games)) {
            foreach ($games as $gameArray) {
                $game = $this->gameRepository->findOneBySteamAppId($gameArray['appid']);
                if (!is_null($game)) {
                    $this->createGameStatsService->execute($user, $game);
                }
            }
        }
    }
}
