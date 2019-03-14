<?php

namespace App\Tests\Service;

use App\Service\SteamGameService;
use PHPUnit\Framework\TestCase;

class SteamGameTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function testGetUsersListOfGames()
    {
        $steamGameService = new SteamGameService();
        $actualList = $steamGameService->getUsersListOfGames('123');
    }
}
