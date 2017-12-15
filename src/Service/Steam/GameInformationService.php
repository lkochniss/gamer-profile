<?php

namespace App\Service\Steam;

use App\Service\Steam\Api\GameApiClientService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($game[$appId]['success'] === false){
            return [];
        }

        return $game[$appId]['data'];
    }
}
