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
        $gameEntity = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if (is_null($gameEntity)) {
            $status = $this->createGame($steamAppId);
        } else {
            $this->persistGame($gameEntity);
            $this->reportService->addEntryToList('Updated game ' . $gameEntity->getName(), ReportService::UPDATED_GAME);
            $status = 'U';
        }

        return $status;
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
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetRecentGames(): bool
    {
        $games = $this->gameRepository->getRecentlyPlayedGames();
        foreach ($games as $game) {
            $game->setRecentlyPlayed(0);
            $this->gameRepository->save($game);
        }

        return true;
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
     * @param $steamAppId
     * @return string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createGame($steamAppId): string
    {
        $gameArray = $this->gameInformationService->getInformationForAppId($steamAppId);
        if (!empty($gameArray)) {
            $gameEntity = new Game();
            $gameEntity->setName($gameArray['name']);
            $gameEntity->setHeaderImagePath($gameArray['header_image']);
            $gameEntity->setSteamAppId($steamAppId);
            $this->reportService->addEntryToList('New game ' . $gameEntity->getName(), ReportService::NEW_GAME);
            $this->persistGame($gameEntity);
            $status = 'N';
        } else {
            $this->reportService->addEntryToList($steamAppId, ReportService::FIND_GAME_ERROR);
            $status = 'F';
        }

        return $status;
    }

    /**
     * @param Game $gameEntity
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function persistGame(Game $gameEntity): void
    {
        $steamAppId = $gameEntity->getSteamAppId();

        $recentlyPlayed = array_key_exists(
            'playtime_2weeks',
            $this->myGames[$steamAppId]
        ) ? $this->myGames[$steamAppId]['playtime_2weeks'] : 0;

        $gameEntity->setRecentlyPlayed($recentlyPlayed);
        $gameEntity->setTimePlayed($this->myGames[$steamAppId]['playtime_forever']);
        $this->gameRepository->save($gameEntity);
    }
}
