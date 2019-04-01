<?php

namespace App\Service\GameStats;

use App\Service\Security\UserProvider;

class UpdateAchievementForAllUsersService
{
    /**
     * @var UpdateAchievementForUserService
     */
    private $updateAchievementForUserService;

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * UpdateAchievementForAllUsersService constructor.
     * @param UpdateAchievementForUserService $updateAchievementForUserService
     * @param   UserProvider $userProvider
     */
    public function __construct(
        UpdateAchievementForUserService $updateAchievementForUserService,
        UserProvider $userProvider
    ) {
        $this->updateAchievementForUserService = $updateAchievementForUserService;
        $this->userProvider = $userProvider;
    }


    public function execute(): void
    {
        $users = $this->userProvider->loadUsers();

        foreach ($users as $user) {
            $this->updateAchievementForUserService->execute($user);
        }
    }
}
