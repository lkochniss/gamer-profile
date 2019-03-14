<?php

namespace App\Service\Transformation;

use App\Entity\Game;
use App\Entity\JSON\JsonGame;
use App\Service\Api\GameApiClientService;

/**
 * Class GameInformationService
 */
class GameInformationService
{
    /**
     * @var GameApiClientService
     */
    private $gameApiClientService;

    /**
     * GameInformationService constructor.
     *
     * @param GameApiClientService $gameApiClientService
     */
    public function __construct(GameApiClientService $gameApiClientService)
    {
        $this->gameApiClientService = $gameApiClientService;
    }

    /**
     * @param int $steamAppId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getGameInformationForSteamAppId(int $steamAppId): array
    {
        $game = $this->gameApiClientService->get('/api/appdetails?appids=' . $steamAppId);

        if ($game[$steamAppId]['success'] === false) {
            return [];
        }

        return $game[$steamAppId]['data'];
    }

    /**
     * @param int $steamAppId
     * @return JsonGame|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getGameInformationEntityForSteamAppId(int $steamAppId): ?JsonGame
    {
        $gameInformation = $this->getGameInformationForSteamAppId($steamAppId);
        if (empty($gameInformation)) {
            return null;
        }

        return new JsonGame($gameInformation);
    }

    /**
     * @param Game $game
     * @return Game|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function addToGame(Game $game): ?Game
    {
        $gameInformation = $this->getGameInformationEntityForSteamAppId($game->getSteamAppId());
        if ($gameInformation === null) {
            return $game;
        }

        $game->setName($gameInformation->getName());
        $game->setHeaderImagePath($gameInformation->getHeaderImagePath());
        $game->setReleaseDate($gameInformation->getReleaseDate());

        return $game;
    }
}
