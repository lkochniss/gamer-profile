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
        $myGames = \GuzzleHttp\json_decode($gamesOwnedResponse->getBody(), true);

        return $myGames['response']['games'];
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function synchronizeMyGames() : array
    {
        $mySteamGames = $this->getMyGames();

        foreach ($mySteamGames as $mySteamGame) {
            $this->createOrUpdateGame($mySteamGame['appid']);
        }

        return $this->reportService->getSummary();
    }

    /**
     * @param $steamAppId
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createOrUpdateGame($steamAppId): void
    {
        $myGame = $this->gameInformationService->getInformationForAppId($steamAppId);
        if (!empty($myGame)){
            $this->getGameInformationBySteamAppId($myGame);
        }else{
            $this->reportService->addEntryToList('Error on appId ' . $steamAppId, ReportService::FIND_GAME_ERROR);
        }
    }

    /**
     * @param array $gameArray
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getGameInformationBySteamAppId(array $gameArray) : void
    {
        $gameEntity = $this->gameRepository->findOneBySteamAppId($gameArray['steam_appid']);

        if (is_null($gameEntity)){
            $gameEntity = new Game();
            $this->reportService->addEntryToList('New game ' . $gameArray['name'], ReportService::NEW_GAME);
        }else {
            $this->reportService->addEntryToList('Updated game ' . $gameArray['name'], ReportService::UPDATED_GAME);
        }

        $gameEntity->setName($gameArray['name']);
        $gameEntity->setSteamAppId($gameArray['steam_appid']);

        $this->gameRepository->save($gameEntity);
    }
}
