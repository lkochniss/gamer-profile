<?php

namespace App\Service\Steam;

use App\Service\Transformation\GameUserInformationService;

class CreateGamesForUserService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var CreateGameService
     */
    private $createGameService;

    /**
     * CreateGamesForUserService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param CreateGameService $createGameService
     */
    public function __construct(GameUserInformationService $gameUserInformationService, CreateGameService $createGameService)
    {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->createGameService = $createGameService;
    }

    /**
     * @param string $steamUserId
     */
    public function execute(string $steamUserId)
    {
        $games = $this->gameUserInformationService->getAllGames($steamUserId);

        if (!empty($games)) {
            foreach ($games as $gameArray) {
                $this->createGameService->execute($gameArray['appid']);
            }
        }
    }
}
