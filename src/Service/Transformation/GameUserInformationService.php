<?php

namespace App\Service\Transformation;

use App\Entity\JSON\JsonAchievement;
use App\Entity\JSON\JsonPlaytime;
use App\Service\Api\UserApiClientService;
use GuzzleHttp\Exception\ClientException;

/**
 * Class GameUserInformationService
 */
class GameUserInformationService
{
    /**
     * @var UserApiClientService
     */
    private $userApiClientService;


    /**
     * GameUserInformationService constructor.
     * @param UserApiClientService $userApiClientService
     */
    public function __construct(UserApiClientService $userApiClientService)
    {
        $this->userApiClientService = $userApiClientService;
    }

    /**
     * @param int $steamUserId
     * @return array
     */
    public function getAllGames(int $steamUserId): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetOwnedGames/v0001/', $steamUserId);
    }

    /**
     * @param int $steamUserId
     * @return array
     */
    public function getRecentlyPlayedGames(int $steamUserId): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetRecentlyPlayedGames/v0001/', $steamUserId);
    }

    /**
     * @param int $steamAppId
     * @param int $steamUserId
     * @return JsonAchievement
     */
    public function getAchievementsForGame(int $steamAppId, int $steamUserId): JsonAchievement
    {
        try {
            $userAchievements = $this->userApiClientService->get(
                '/ISteamUserStats/GetPlayerAchievements/v0001/?appid=' . $steamAppId,
                $steamUserId
            );
            return new JsonAchievement($userAchievements);
        } catch (ClientException $clientException) {
            return new JsonAchievement();
        }
    }

    /**
     * @param int $steamAppId
     * @param int $steamUserId
     * @return array
     */
    public function getUserInformationForSteamAppId(int $steamAppId, int $steamUserId): array
    {
        $gamesArray = $this->getAllGames($steamUserId);

        return array_key_exists($steamAppId, $gamesArray) ? $gamesArray[$steamAppId] : [];
    }

    /**
     * @param int $steamAppId
     * @param int $steamUserId
     * @return JsonPlaytime|null
     */
    public function getPlaytimeForGame(int $steamAppId, int $steamUserId): JsonPlaytime
    {
        $userInformation = $this->getUserInformationForSteamAppId($steamAppId, $steamUserId);

        return new JsonPlaytime($userInformation);
    }

    /**
     * @param string $apiEndpoint
     * @param int $steamUserId
     * @return array
     */
    private function getGamesFromApiEndpoint(string $apiEndpoint, int $steamUserId): array
    {
        $gamesArray = $this->userApiClientService->get($apiEndpoint, $steamUserId);
        $games = [];

        if (!array_key_exists('response', $gamesArray) ||
            !array_key_exists('games', $gamesArray['response'])) {
            return [];
        }

        foreach ($gamesArray['response']['games'] as $game) {
            $games[$game['appid']] = $game;
        }

        return $games;
    }
}
