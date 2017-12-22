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
     * @var array
     */
    private $myGames = [];

    /**
     * GamesOwnedService constructor.
     *
     * @param UserApiClientService   $userApiClientService
     * @param GameInformationService $gameInformationService
     * @param GameRepository         $gameRepository
     */
    public function __construct(
        UserApiClientService $userApiClientService,
        GameInformationService $gameInformationService,
        GameRepository $gameRepository
    ) {
        $this->userApiClientService = $userApiClientService;
        $this->gameInformationService = $gameInformationService;
        $this->gameRepository = $gameRepository;
        $this->reportService = new ReportService();
    }

    /**
     * @return array
     */
    public function getAllMyGames() : array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetOwnedGames/v0001/');
    }

    /**
     * @return array
     */
    public function getMyRecentlyPlayedGames() : array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetRecentlyPlayedGames/v0001/');
    }

    /**
     * @param $steamAppId
     * @return string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrUpdateGame($steamAppId): string
    {
        $myGame = $this->gameInformationService->getInformationForAppId($steamAppId);
        if (!empty($myGame)) {
            return $this->getGameInformationBySteamAppId($myGame);
        } else {
            $this->reportService->addEntryToList($steamAppId, ReportService::FIND_GAME_ERROR);
            return 'F';
        }
    }

    /**
     * @return array
     */
    public function getSummary(): array
    {
        return $this->reportService->getSummary();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->reportService->getDetailsFor(ReportService::FIND_GAME_ERROR);
    }

    /**
     * @param string $apiEndpoint
     * @return array
     */
    private function getGamesFromApiEndpoint(string $apiEndpoint): array
    {
        $gamesOwnedResponse = $this->userApiClientService->get($apiEndpoint);
        $myGames = \GuzzleHttp\json_decode($gamesOwnedResponse->getBody(), true);

        foreach ($myGames['response']['games'] as $game) {
            $this->myGames[$game['appid']] = $game;
        }

        return $this->myGames;
    }

    /**
     * @param array $gameArray
     * @return string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getGameInformationBySteamAppId(array $gameArray) : string
    {
        $steamAppId = $gameArray['steam_appid'];
        $gameEntity = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if (is_null($gameEntity)) {
            $gameEntity = new Game();
            $this->reportService->addEntryToList('New game ' . $gameArray['name'], ReportService::NEW_GAME);
            $status = 'N';
        } else {
            $this->reportService->addEntryToList('Updated game ' . $gameArray['name'], ReportService::UPDATED_GAME);
            $status = 'U';
        }

        $recentlyPlayed = array_key_exists(
            'playtime_2weeks',
            $this->myGames[$steamAppId]
        ) ? $this->myGames[$steamAppId]['playtime_2weeks'] : 0;

        $gameEntity->setName($gameArray['name']);
        $gameEntity->setSteamAppId($steamAppId);
        $gameEntity->setRecentlyPlayed($recentlyPlayed);
        $gameEntity->setTimePlayed($this->myGames[$steamAppId]['playtime_forever']);

        $this->gameRepository->save($gameEntity);

        return $status;
    }
}
