<?php

namespace App\Service\Steam;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\ReportService;
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
     * @var ReportService
     */
    private $reportService;

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
        $this->reportService = new ReportService();
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
     * @return array
     */
    public function synchronizeMyGames() : array
    {
        $mySteamGames = $this->getMyGames();

        foreach ($mySteamGames as $mySteamGame) {
            $myGame = $this->gameInformationService->getInformationForAppId($mySteamGame['appid']);
            $this->createOrUpdateGame($myGame);
        }

        return $this->reportService->getSummary();
    }

    /**
     * @param array $gameArray
     */
    private function createOrUpdateGame(array $gameArray) : void
    {
        $gameEntity = $this->gameRepository->findOneBySteamAppId($gameArray['steam_appid']);

        if (is_null($gameEntity)){
            $gameEntity = new Game();
            $this->reportService->addEntryToList('New game ' . $gameArray['name'], ReportService::NEW_GAME);
        }else {
            $this->reportService->addEntryToList('Updated game ' . $gameArray['name'], ReportService::UPDATED_GAME);
        }

        $this->gameRepository->save($gameEntity);
    }
}
