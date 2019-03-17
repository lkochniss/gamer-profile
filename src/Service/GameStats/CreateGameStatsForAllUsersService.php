<?php

namespace App\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameStatsRepository;
use App\Repository\UserRepository;
use App\Service\Transformation\GameUserInformationService;

class CreateGameStatsForAllUsersService
{

    /**
     * @var CreateGameStatsForUsersGamesService
     */
    private $createGameStatsForUsersGamesService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * CreateGameStatsForAllUsersService constructor.
     * @param CreateGameStatsForUsersGamesService $createGameStatsForUsersGamesService
     * @param UserRepository $userRepository
     */
    public function __construct(
        CreateGameStatsForUsersGamesService $createGameStatsForUsersGamesService,
        UserRepository $userRepository
    ) {
        $this->createGameStatsForUsersGamesService = $createGameStatsForUsersGamesService;
        $this->userRepository = $userRepository;
    }

    public function execute()
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->createGameStatsForUsersGamesService->execute($user);
        }
    }
}
