<?php

namespace App\Service\Steam;

use App\Repository\UserRepository;

class GamesForAllUsersService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var GamesForUserService;
     */
    private $gamesForUserService;

    /**
     * GamesForAllUsersService constructor.
     * @param UserRepository $userRepository
     * @param GamesForUserService $gamesForUserService
     */
    public function __construct(UserRepository $userRepository, GamesForUserService $gamesForUserService)
    {
        $this->userRepository = $userRepository;
        $this->gamesForUserService = $gamesForUserService;
    }

    public function create(): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->gamesForUserService->create($user->getSteamId());
        }
    }

    public function updateRecentlyPlayed(): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->gamesForUserService->updateRecentlyPlayed($user->getSteamId());
        }
    }
}
