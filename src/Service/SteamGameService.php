<?php

namespace App\Service;

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

    public function fetchNewGames()
    {
        $users = $this->userRepository->findAll();

        /**
         * @var User $user
         */
        foreach ($users as $user) {
            $games = $this->gameUserInformationService->getAllGames($user->getSteamId());

            if (empty($games)) {
                return;
            }

            foreach ($games['response']['games'] as $gameArray) {

                $steamAppId = $gameArray['appid'];
                $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

                if (is_null($game)) {
                    $this->gameInformationService->getGameInformationForSteamAppId($steamAppId);
                }
            }
        }
    }
}
