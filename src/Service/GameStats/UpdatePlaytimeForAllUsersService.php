<?php

namespace App\Service\GameStats;

use App\Service\Security\UserProvider;

class UpdatePlaytimeForAllUsersService
{
    /**
     * @var UpdatePlaytimeForUserService
     */
    private $updatePlaytimeForUserService;

    /**
     * @var UpdateGameStatusForUserService;
     */
    private $updateGameStatusForUserService;

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * UpdatePlaytimeForAllUsersService constructor.
     * @param UpdatePlaytimeForUserService $updatePlaytimeForUserService
     * @param UpdateGameStatusForUserService $updateGameStatusForUserService
     * @param UserProvider $userProvider
     */
    public function __construct(
        UpdatePlaytimeForUserService $updatePlaytimeForUserService,
        UpdateGameStatusForUserService $updateGameStatusForUserService,
        UserProvider $userProvider
    ) {
        $this->updatePlaytimeForUserService = $updatePlaytimeForUserService;
        $this->updateGameStatusForUserService = $updateGameStatusForUserService;
        $this->userProvider = $userProvider;
    }

    public function execute(): void
    {
        $users = $this->userProvider->loadUsers();

        foreach ($users as $user) {
            $this->updatePlaytimeForUserService->execute($user);
            $this->updateGameStatusForUserService->setStatusPausedForPlayingGamesWithoutRecentPlayed($user);
            $this->updateGameStatusForUserService->setStatusPlayingForRecentPlayed($user);
        }
    }
}
