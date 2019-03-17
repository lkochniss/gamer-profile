<?php

namespace App\Service\Transformation;

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
     */
    private function getGameInformationForSteamAppId(int $steamAppId): array
    {
        $game = $this->gameApiClientService->get('/api/appdetails?appids=' . $steamAppId);

        if (empty($game) || $game[$steamAppId]['success'] === false) {
            return [];
        }

        return $game[$steamAppId]['data'];
    }

    /**
     * @param int $steamAppId
     * @return JsonGame
     */
    public function getGameInformationEntityForSteamAppId(int $steamAppId): JsonGame
    {
        $gameInformation = $this->getGameInformationForSteamAppId($steamAppId);

        return new JsonGame($gameInformation);
    }
}
