<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Service\Transformation\GameInformationService;
use App\Service\Transformation\GameUserInformationService;

class SteamGameService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var GameInformationService
     */
    private $gameInformationService;

    /**
     * SteamGameService constructor.
     * @param UserRepository $userRepository
     * @param GameRepository $gameRepository
     * @param GameUserInformationService $gameUserInformationService
     * @param GameInformationService $gameInformationService
     */
    public function __construct(UserRepository $userRepository, GameRepository $gameRepository, GameUserInformationService $gameUserInformationService, GameInformationService $gameInformationService)
    {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
        $this->gameUserInformationService = $gameUserInformationService;
        $this->gameInformationService = $gameInformationService;
    }

    public function fetchNewGames(): void
    {
        $users = $this->userRepository->findAll();

        /**
         * @var User $user
         */
        foreach ($users as $user) {
            $games = $this->gameUserInformationService->getAllGames($user->getSteamId());

            if (!empty($games)) {
                foreach ($games['response']['games'] as $gameArray) {

                    $steamAppId = $gameArray['appid'];
                    $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

                    if (is_null($game)) {
                        $gameInformation = $this->gameInformationService->getGameInformationForSteamAppId($steamAppId);

                        $game = new Game();
                        $game->setSteamAppId($steamAppId);
                        $game->setName(Game::NAME_FAILED);
                        $game->setHeaderImagePath(Game::IMAGE_FAILED);

                        if ($gameInformation) {
                            $game->setName($gameInformation['name']);
                            $game->setHeaderImagePath($gameInformation['header_image']);
                        }

                        $this->gameRepository->save($game);
                    }
                }
            }
        }
    }
}
