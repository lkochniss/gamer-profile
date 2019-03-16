<?php

namespace App\Service\GameStats;

use App\Repository\UserRepository;

class UpdatePlaytimeForAllUsersService
{
    /**
     * @var UpdatePlaytimeForUserService
     */
    private $updatePlaytimeForUserService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UpdatePlaytimeForAllUsersService constructor.
     * @param UpdatePlaytimeForUserService $updatePlaytimeForUserService
     * @param UserRepository $userRepository
     */
    public function __construct(UpdatePlaytimeForUserService $updatePlaytimeForUserService, UserRepository $userRepository)
    {
        $this->updatePlaytimeForUserService = $updatePlaytimeForUserService;
        $this->userRepository = $userRepository;
    }

    public function execute(): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->updatePlaytimeForUserService->execute($user);
        }
    }
}
