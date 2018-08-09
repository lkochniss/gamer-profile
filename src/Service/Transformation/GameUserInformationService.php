<?php

namespace App\Service\Transformation;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\JSON\JsonAchievement;
use App\Entity\Playtime;
use App\Entity\JSON\JsonPlaytime;
use App\Entity\User;
use App\Repository\GameSessionRepository;
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
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * GameUserInformationService constructor.
     * @param UserApiClientService $userApiClientService
     * @param GameSessionRepository $gameSessionRepository
     */
    public function __construct(
        UserApiClientService $userApiClientService,
        GameSessionRepository $gameSessionRepository
    ) {
        $this->userApiClientService = $userApiClientService;
        $this->gameSessionRepository = $gameSessionRepository;
    }

    /**
     * @param int $steamUserId
     * @return array
     * @throws JsonException
     */
    public function getAllGames(int $steamUserId): array
    {
        return $this->getGamesFromApiEndpoint('/IPlayerService/GetOwnedGames/v0001/', $steamUserId);
    }

    /**
     * @param int $steamUserId
     * @return array
     * @throws JsonException
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
            return new JsonAchievement(\GuzzleHttp\json_decode($userAchievements->getBody(), true));
        } catch (ClientException $clientException) {
            return new JsonAchievement();
        }
    }

    /**
     * @param int $steamAppId
     * @param int $steamUserId
     * @return array
     * @throws JsonException
     */
    public function getUserInformationForSteamAppId(int $steamAppId, int $steamUserId): array
    {
        $gamesArray = $this->getRecentlyPlayedGames($steamUserId);

        if (array_key_exists($steamAppId, $gamesArray)) {
            return $gamesArray[$steamAppId];
        }

        $gamesArray = $this->getAllGames($steamUserId);

        return $gamesArray[$steamAppId];
    }

    /**
     * @param int $steamAppId
     * @param int $steamUserId
     * @return JsonPlaytime|null
     * @throws JsonException
     */
    public function getUserInformationEntityForSteamAppId(int $steamAppId, int $steamUserId): ?JsonPlaytime
    {
        $userInformation = $this->getUserInformationForSteamAppId($steamAppId, $steamUserId);
        if (empty($userInformation)) {
            return null;
        }

        return new JsonPlaytime($userInformation);
    }

    /**
     * @param string $apiEndpoint
     * @param int $steamUserId
     * @return array
     * @throws JsonException
     */
    private function getGamesFromApiEndpoint(string $apiEndpoint, int $steamUserId): array
    {
        $gamesOwnedResponse = $this->userApiClientService->get($apiEndpoint, $steamUserId);
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
     * @param Playtime $playtime
     * @return Playtime|null
     * @throws JsonException
     */
    public function addPlaytime(Playtime $playtime): ?Playtime
    {
        $userInformation = $this->getUserInformationEntityForSteamAppId(
            $playtime->getGame()->getSteamAppId(),
            $playtime->getUser()->getSteamId()
        );

        if ($userInformation === null) {
            return null;
        }

        $playtime->setOverallPlaytime($userInformation->getOverallPlaytime());
        $playtime->setRecentPlaytime($userInformation->getRecentPlaytime());

        return $playtime;
    }

    /**
     * @param Achievement $achievement
     * @param int $steamUserId
     * @return Achievement
     */
    public function addAchievements(Achievement $achievement, int $steamUserId): Achievement
    {
        $gameAchievements = $this->getAchievementsForGame($achievement->getGame()->getSteamAppId(), $steamUserId);

        if (!empty($gameAchievements) && array_key_exists('achievements', $gameAchievements['playerstats'])) {
            $achievement->setPlayerAchievements($gameAchievements['playerstats']);
            $achievement->setOverallAchievements($gameAchievements['playerstats']);
        }

        return $achievement;
    }

    /**
     * @param Playtime $playtime
     * @return GameSession|null
     * @throws JsonException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addSession(Playtime $playtime): ?GameSession
    {
        $date = new \DateTime('today 00:00:00');

        $userInformation = $this->getUserInformationEntityForSteamAppId(
            $playtime->getGame()->getSteamAppId(),
            $playtime->getUser()->getSteamId()
        );

        if ($userInformation === null) {
            return null;
        }

        $gameSession  = $this->gameSessionRepository->findOneBy([
            'game' => $playtime->getGame(),
            'user' => $playtime->getUser(),
            'date' => $date
        ]);

        if ($gameSession === null) {
            $gameSession = new GameSession($playtime->getGame(), $playtime->getUser(), $date);
        }

        if ($userInformation->getRecentPlaytime() > 0 &&
            $userInformation->getOverallPlaytime() > $playtime->getOverallPlaytime()
        ) {
            $duration = $userInformation->getOverallPlaytime() - $playtime->getGame()->getTimePlayed();
            $gameSession->setDuration($duration);
            $this->gameSessionRepository->save($gameSession);
        }

        return $gameSession;
    }
}
