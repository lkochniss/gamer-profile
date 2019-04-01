<?php

namespace App\Service\GameStats;

use App\Service\Security\UserProvider;

class CreateGameStatsForAllUsersService
{

    /**
     * @var CreateGameStatsForUsersGamesService
     */
    private $createGameStatsForUsersGamesService;

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * CreateGameStatsForAllUsersService constructor.
     * @param CreateGameStatsForUsersGamesService $createGameStatsForUsersGamesService
     * @param UserProvider $userProvider
     */
    public function __construct(
        CreateGameStatsForUsersGamesService $createGameStatsForUsersGamesService,
        UserProvider $userProvider
    ) {
        $this->createGameStatsForUsersGamesService = $createGameStatsForUsersGamesService;
        $this->userProvider = $userProvider;
    }

    public function execute()
    {
        $users = $this->userProvider->loadUsers();

        foreach ($users as $user) {
            $this->createGameStatsForUsersGamesService->execute($user);
        }
    }
}
