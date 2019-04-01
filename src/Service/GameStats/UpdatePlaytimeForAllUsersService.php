<?php

namespace App\Service\GameStats;

use App\Service\Security\AwsCognitoClient;

class UpdatePlaytimeForAllUsersService
{
    /**
     * @var UpdatePlaytimeForUserService
     */
    private $updatePlaytimeForUserService;

    /**
     * @var AwsCognitoClient
     */
    private $cognitoClient;

    /**
     * UpdatePlaytimeForAllUsersService constructor.
     * @param UpdatePlaytimeForUserService $updatePlaytimeForUserService
     * @param AwsCognitoClient $cognitoClient
     */
    public function __construct(
        UpdatePlaytimeForUserService $updatePlaytimeForUserService,
        AwsCognitoClient $cognitoClient
    ) {
        $this->updatePlaytimeForUserService = $updatePlaytimeForUserService;
        $this->cognitoClient = $cognitoClient;
    }

    public function execute(): void
    {
        $users = $this->cognitoClient->getAllUsers();

        foreach ($users as $user) {
            $this->updatePlaytimeForUserService->execute($user);
        }
    }
}
