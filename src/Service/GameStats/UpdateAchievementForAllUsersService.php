<?php

namespace App\Service\GameStats;

use App\Service\Security\AwsCognitoClient;

class UpdateAchievementForAllUsersService
{
    /**
     * @var UpdateAchievementForUserService
     */
    private $updateAchievementForUserService;

    /**
     * @var AwsCognitoClient
     */
    private $cognitoClient;

    /**
     * UpdateAchievementForAllUsersService constructor.
     * @param UpdateAchievementForUserService $updateAchievementForUserService
     * @param AwsCognitoClient $cognitoClient
     */
    public function __construct(
        UpdateAchievementForUserService $updateAchievementForUserService,
        AwsCognitoClient $cognitoClient
    ) {
        $this->updateAchievementForUserService = $updateAchievementForUserService;
        $this->cognitoClient = $cognitoClient;
    }


    public function execute(): void
    {
        $users = $this->cognitoClient->getAllUsers();

        foreach ($users as $user) {
            $this->updateAchievementForUserService->execute($user);
        }
    }
}
