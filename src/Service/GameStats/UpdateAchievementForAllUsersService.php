<?php

namespace App\Service\GameStats;

use App\Repository\UserRepository;

class UpdateAchievementForAllUsersService
{
    /**
     * @var UpdateAchievementForUserService
     */
    private $updateAchievementForUserService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UpdateAchievementForAllUsersService constructor.
     * @param UpdateAchievementForUserService $updateAchievementForUserService
     * @param UserRepository $userRepository
     */
    public function __construct(
        UpdateAchievementForUserService $updateAchievementForUserService,
        UserRepository $userRepository
    ) {
        $this->updateAchievementForUserService = $updateAchievementForUserService;
        $this->userRepository = $userRepository;
    }


    public function execute(): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->updateAchievementForUserService->execute($user);
        }
    }
}
