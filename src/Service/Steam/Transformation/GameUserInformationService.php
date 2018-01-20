<?php

namespace App\Service\Steam\Transformation;

use App\Entity\UserInformation;
use App\Service\Steam\Api\UserApiClientService;

/**
 * Class GamesOwnedService
 */
class GameUserInformationService
{
    /**
     * @var UserApiClientService
     */
    private $userApiClientService;

    /**
     * GamesOwnedService constructor.
     *
     * @param UserApiClientService $userApiClientService
     */
    public function __construct(UserApiClientService $userApiClientService)
    {
        $this->userApiClientService = $userApiClientService;
    }

    /**
     * @return array
     */
    public function getAllGames(): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetOwnedGames/v0001/');
    }

    /**
     * @return array
     */
    public function getRecentlyPlayedGames(): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetRecentlyPlayedGames/v0001/');
    }

    /**
     * @param int $steamAppId
     * @return array
     */
    public function getUserInformationForSteamAppId(int $steamAppId): array
    {
        $gamesArray = $this->getRecentlyPlayedGames();

        if (array_key_exists($steamAppId, $gamesArray)){
            return $gamesArray[$steamAppId];
        }

        $gamesArray = $this->getAllGames();

        return $gamesArray[$steamAppId];
    }

    /**
     * @param int $steamAppId
     * @return UserInformation|null
     */
    public function getUserInformationEntityForSteamAppId(int $steamAppId): ?UserInformation
    {
        $userInformation = $this->getUserInformationForSteamAppId($steamAppId);
        if (empty($userInformation)) {
            return null;
        }

        return new UserInformation($userInformation);
    }

    /**
     * @param string $apiEndpoint
     * @return array
     */
    private function getGamesFromApiEndpoint(string $apiEndpoint): array
    {
        $gamesOwnedResponse = $this->userApiClientService->get($apiEndpoint);
        $gamesArray = \GuzzleHttp\json_decode($gamesOwnedResponse->getBody(), true);

        $games = [];
        foreach ($gamesArray['response']['games'] as $game) {
            $games[$game['appid']] = $game;
        }

        return $games;
    }
}
