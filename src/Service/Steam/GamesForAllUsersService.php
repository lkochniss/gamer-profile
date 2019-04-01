<?php

namespace App\Service\Steam;

use App\Service\Security\UserProvider;

class GamesForAllUsersService
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @var GamesForUserService;
     */
    private $gamesForUserService;

    /**
     * GamesForAllUsersService constructor.
     * @param UserProvider $userProvider
     * @param GamesForUserService $gamesForUserService
     */
    public function __construct(UserProvider $userProvider, GamesForUserService $gamesForUserService)
    {
        $this->userProvider = $userProvider;
        $this->gamesForUserService = $gamesForUserService;
    }

    public function create(): void
    {
        $users = $this->userProvider->loadUsers();

        foreach ($users as $user) {
            $this->gamesForUserService->create($user->getSteamId());
        }
    }

    public function updateRecentlyPlayed(): void
    {
        $users = $this->userProvider->loadUsers();

        foreach ($users as $user) {
            $this->gamesForUserService->updateRecentlyPlayed($user->getSteamId());
        }
    }
}
