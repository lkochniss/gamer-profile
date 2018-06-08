<?php

namespace App\Service\Transformation;

use App\Entity\GameInformation;
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
    public function getGameInformationForSteamAppId(int $steamAppId): array
    {
        $gamesOwnedResponse = $this->gameApiClientService->get('/api/appdetails?appids=' . $steamAppId);
        $game = json_decode($gamesOwnedResponse->getBody(), true);

        if ($game[$steamAppId]['success'] === false) {
            return [];
        }

        return $game[$steamAppId]['data'];
    }

    /**
     * @param int $steamAppId
     * @return GameInformation|null
     */
    public function getGameInformationEntityForSteamAppId(int $steamAppId): ?GameInformation
    {
        $gameInformation = $this->getGameInformationForSteamAppId($steamAppId);
        if (empty($gameInformation)) {
            return null;
        }

        return new GameInformation($gameInformation);
    }
}
