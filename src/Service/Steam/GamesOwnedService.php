<?php

namespace App\Service\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
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
     * @param UserApiClientService $userApiClientService
     * @param GameInformationService $gameInformationService
     * @param GameRepository $gameRepository
     */
    public function __construct(
        UserApiClientService $userApiClientService,
        GameInformationService $gameInformationService,
        GameRepository $gameRepository
    )
    {
        $this->userApiClientService = $userApiClientService;
        $this->gameInformationService = $gameInformationService;
        $this->gameRepository = $gameRepository;
        $this->reportService = new ReportService();
    }

    /**
     * @return array
     */
    public function getAllMyGames(): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetOwnedGames/v0001/');
    }

    /**
     * @return array
     */
    public function getMyRecentlyPlayedGames(): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetRecentlyPlayedGames/v0001/');
    }

    /**
     * @param $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createGameIfNotExist($steamAppId): string
    {
        $gameEntity = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if (is_null($gameEntity)) {
            return $this->createNewGame($steamAppId);
        }

        $this->reportService->addEntryToList($gameEntity->getName(), ReportService::SKIPPED_GAME);

        return 'S';
    }

    /**
     * @param $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrUpdateGame($steamAppId): string
    {
        $gameEntity = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if (is_null($gameEntity)) {
            return $this->createNewGame($steamAppId);
        }

        return $this->updateExistingGame($gameEntity);
    }

    /**
     * @param Game $gameEntity
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateExistingGame(Game $gameEntity): string
    {
        $steamAppId = $gameEntity->getSteamAppId();
        $gameArray = $this->gameInformationService->getInformationForAppId($steamAppId);
        if (!empty($gameArray)) {
            $gameEntity->setName($gameArray['name']);
            $gameEntity->setHeaderImagePath($gameArray['header_image']);
            $price = array_key_exists('price_overview', $gameArray) ? $gameArray['price_overview']['final'] / 100 : 0;
            $currency = array_key_exists('price_overview', $gameArray) ?
                $gameArray['price_overview']['currency'] : 'USD';
            $gameEntity->setPrice($price);
            $gameEntity->setCurrency($currency);
            $gameEntity->setSteamAppId($steamAppId);
            $gameEntity->setModifiedAt();
            $this->reportService->addEntryToList($gameEntity->getName(), ReportService::UPDATED_GAME);
            $gameEntity = $this->addSessionForExistingGameIfExists($gameEntity);
            $this->persistGame($gameEntity);
            $status = 'U';
        } else {
            $this->reportService->addEntryToList($steamAppId, ReportService::FIND_GAME_ERROR);
            $status = 'F';
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
     * @return array
     */
    public function getUpdates(): array
    {
        return $this->reportService->getDetailsFor(ReportService::UPDATED_GAME);
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\ORMException
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
     * @param Game $game
     * @return Game
     */
    private function addSessionForNewGameIfExists(Game $game)
    {
        if (array_key_exists('playtime_2weeks', $this->myGames[$game->getSteamAppId()]) &&
            $this->myGames[$game->getSteamAppId()]['playtime_forever'] ===
            $this->myGames[$game->getSteamAppId()]['playtime_2weeks']) {
            $gameSession = new GameSession();
            $gameSession->setDuration($this->myGames[$game->getSteamAppId()]['playtime_forever']);
            $game->addGameSession($gameSession);
        }

        return $game;
    }

    /**
     * @param Game $game
     * @return Game
     */
    private function addSessionForExistingGameIfExists(Game $game)
    {
        if (array_key_exists('playtime_2weeks', $this->myGames[$game->getSteamAppId()]) &&
            $this->myGames[$game->getSteamAppId()]['playtime_2weeks'] > 0 &&
            $diff = $this->myGames[$game->getSteamAppId()]['playtime_forever'] - $game->getTimePlayed()) {
            $gameSession = new GameSession();
            $gameSession->setDuration($diff);
            $game->addGameSession($gameSession);
        }

        return $game;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createNewGame($steamAppId): string
    {
        $gameArray = $this->gameInformationService->getInformationForAppId($steamAppId);
        if (!empty($gameArray)) {
            $gameEntity = new Game();
            $gameEntity->setName($gameArray['name']);
            $gameEntity->setHeaderImagePath($gameArray['header_image']);
            $price = array_key_exists('price_overview', $gameArray) ? $gameArray['price_overview']['final'] / 100 : 0;
            $currency = array_key_exists('price_overview', $gameArray) ?
                $gameArray['price_overview']['currency'] : 'USD';
            $gameEntity->setPrice($price);
            $gameEntity->setCurrency($currency);
            $gameEntity->setSteamAppId($steamAppId);
            $this->reportService->addEntryToList($gameEntity->getName(), ReportService::NEW_GAME);
            $gameEntity = $this->addSessionForNewGameIfExists($gameEntity);
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
     * @throws \Doctrine\ORM\ORMException
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
