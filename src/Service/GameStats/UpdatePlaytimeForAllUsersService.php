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
     * @var UserProvider
     */
    private $userProvider;

    /**
     * UpdatePlaytimeForAllUsersService constructor.
     * @param UpdatePlaytimeForUserService $updatePlaytimeForUserService
     * @param UserProvider $userProvider
     */
    public function __construct(
        UpdatePlaytimeForUserService $updatePlaytimeForUserService,
        UserProvider $userProvider
    ) {
        $this->updatePlaytimeForUserService = $updatePlaytimeForUserService;
        $this->userProvider = $userProvider;
    }

    public function execute(): void
    {
        $users = $this->userProvider->loadUsers();

        foreach ($users as $user) {
            $this->updatePlaytimeForUserService->execute($user);
        }
    }
}
