<?php

namespace App\Service\Steam;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Steam\Api\UserApiClientService;

/**
 * Class GamesOwnedService
 */
class GamesOwnedService
{
    /**
     * @var UserApiClientService
     */
    private $userApiClientService;

    /**
     * @var GameInformationService
     */
    private $gameInformationService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * GamesOwnedService constructor.
     *
     * @param UserApiClientService $userApiClientService
     * @param GameInformationService $gameInformationService
     * @param GameRepository $gameRepository
     */
    public function __construct(UserApiClientService $userApiClientService, GameInformationService $gameInformationService, GameRepository $gameRepository)
    {
        $this->userApiClientService = $userApiClientService;
        $this->gameInformationService = $gameInformationService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @return array
     */
    public function getMyGames() : array
    {
        $gamesOwnedResponse = $this->userApiClientService->get('/IPlayerService/GetOwnedGames/v0001/');
        $myGames = \GuzzleHttp\json_decode($gamesOwnedResponse->getContent(), true);

        return $myGames['response']['games'];
    }

    /**
     * @return bool
     */
    public function synchronizeMyGames() : bool
    {
        $mySteamGames = $this->getMyGames();

        foreach ($mySteamGames as $mySteamGame) {
            $myGame = $this->gameInformationService->getInformationForAppId($mySteamGame['appid']);
            $this->createOrUpdateGame($myGame);
        }

        return true;
    }

    /**
     * @param array $game
     */
    private function createOrUpdateGame(array $game) : void
    {
        $game = $this->gameRepository->findOneBySteamAppId($game['steam_appid']);

        if (is_null($game)){
            $game = new Game();
            // TODO: Log
        }

        $this->gameRepository->save($game);
    }
}
