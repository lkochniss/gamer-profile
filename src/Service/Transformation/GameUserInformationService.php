<?php

namespace App\Service\Transformation;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\UserInformation;
use App\Service\Api\UserApiClientService;
use GuzzleHttp\Exception\ClientException;
use Nette\Utils\JsonException;

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
     * @throws JsonException
     */
    public function getAllGames(): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetOwnedGames/v0001/');
    }

    /**
     * @return array
     * @throws JsonException
     */
    public function getRecentlyPlayedGames(): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetRecentlyPlayedGames/v0001/');
    }

    /**
     * @param int $appId
     * @return array
     */
    public function getAchievementsForGame(int $appId): array
    {
        try {
            $userAchievements = $this->userApiClientService->get(
                '/ISteamUserStats/GetPlayerAchievements/v0001/?appid=' . $appId
            );
            return \GuzzleHttp\json_decode($userAchievements->getBody(), true);
        } catch (ClientException $clientException) {
            return [];
        }
    }

    /**
     * @param int $steamAppId
     * @return array
     * @throws JsonException
     */
    public function getUserInformationForSteamAppId(int $steamAppId): array
    {
        $gamesArray = $this->getRecentlyPlayedGames();

        if (array_key_exists($steamAppId, $gamesArray)) {
            return $gamesArray[$steamAppId];
        }

        $gamesArray = $this->getAllGames();

        return $gamesArray[$steamAppId];
    }

    /**
     * @param int $steamAppId
     * @return UserInformation|null
     * @throws JsonException
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
     * @throws JsonException
     */
    private function getGamesFromApiEndpoint(string $apiEndpoint): array
    {
        $gamesOwnedResponse = $this->userApiClientService->get($apiEndpoint);
        $gamesArray = \GuzzleHttp\json_decode($gamesOwnedResponse->getBody(), true);

        $games = [];

        if (!array_key_exists('response', $gamesArray) ||
            !array_key_exists('games', $gamesArray['response'])) {
            throw new JsonException('Response Body invalid');
        }

        foreach ($gamesArray['response']['games'] as $game) {
            $games[$game['appid']] = $game;
        }

        return $games;
    }

    /**
     * @param Game $game
     * @return Game|null
     * @throws JsonException
     */
    public function addPlaytime(Game $game): ?Game
    {
        $userInformation = $this->getUserInformationEntityForSteamAppId(
            $game->getSteamAppId()
        );

        if ($userInformation === null) {
            return null;
        }

        $game->setTimePlayed($userInformation->getTimePlayed());
        $game->setRecentlyPlayed($userInformation->getRecentlyPlayed());

        return $game;
    }

    /**
     * @param Game $game
     * @return Game
     */
    public function addAchievements(Game $game): Game
    {
        $gameAchievements = $this->getAchievementsForGame($game->getSteamAppId());

        if (!empty($gameAchievements) && array_key_exists('achievements', $gameAchievements['playerstats'])) {
            $achievements = new Achievement($gameAchievements);
            $game->setPlayerAchievements($achievements->getPlayerAchievements());
            $game->setOverallAchievements($achievements->getOverallAchievements());
        }

        return $game;
    }

    /**
     * @param Game $game
     * @return Game
     * @throws \Nette\Utils\JsonException
     */
    public function addSession(Game $game): Game
    {
        $userInformation = $this->getUserInformationEntityForSteamAppId($game->getSteamAppId());

        if ($userInformation !== null &&
            $userInformation->getRecentlyPlayed() > 0
            && $userInformation->getTimePlayed() > $game->getTimePlayed()
        ) {
            $gameSession = new GameSession();
            $duration = $userInformation->getTimePlayed() - $game->getTimePlayed();
            $gameSession->setDuration($duration);
            $game->addGameSession($gameSession);
        }

        return $game;
    }
}
