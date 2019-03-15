<?php

namespace App\Service\Steam;

use App\Repository\UserRepository;

class CreateGamesForAllUsersService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CreateGamesForUserService;
     */
    private $createGamesForUserService;

    /**
     * CreateGamesForAllUsersService constructor.
     * @param UserRepository $userRepository
     * @param CreateGamesForUserService $createGamesForUserService
     */
    public function __construct(UserRepository $userRepository, CreateGamesForUserService $createGamesForUserService)
    {
        $this->userRepository = $userRepository;
        $this->createGamesForUserService = $createGamesForUserService;
    }


    public function execute()
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->createGamesForUserService->execute($user->getSteamId());
        }
    }
}
