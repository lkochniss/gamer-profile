<?php

namespace App\Service\Steam;

use App\Service\Steam\Api\GameApiClientService;

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
     * @param int $appId
     *
     * @return array
     */
    public function getInformationForAppId(int $appId) : array
    {
        $gamesOwnedResponse = $this->gameApiClientService->get('/api/appdetails?appids=' . $appId);
        $game = json_decode($gamesOwnedResponse->getBody(), true);

        return $game[$appId]['data'];
    }
}
