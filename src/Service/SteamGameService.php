<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Transformation\GameUserInformationService;

class SteamGameService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * SteamGameService constructor.
     * @param UserRepository $userRepository
     * @param GameUserInformationService $gameUserInformationService
     */
    public function __construct(UserRepository $userRepository, GameUserInformationService $gameUserInformationService)
    {
        $this->userRepository = $userRepository;
        $this->gameUserInformationService = $gameUserInformationService;
    }


    public function fetchNewGames()
    {
        $users = $this->userRepository->findAll();

        /**
         * @var User $user
         */
        foreach ($users as $user) {
            $games = $this->gameUserInformationService->getAllGames($user->getSteamId());
        }
    }
}
