<?php

namespace App\Service\Steam;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Transformation\GameInformationService;

class CreateGameService
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var GameInformationService
     */
    private $gameInformationService;

    /**
     * CreateGamesForUser constructor.
     * @param GameRepository $gameRepository
     * @param GameInformationService $gameInformationService
     */
    public function __construct(GameRepository $gameRepository, GameInformationService $gameInformationService)
    {
        $this->gameRepository = $gameRepository;
        $this->gameInformationService = $gameInformationService;
    }

    /**
     * @param string $steamAppId
     */
    public function execute(string $steamAppId)
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if (!is_null($game)) {
            return;
        }

        $gameInformation = $this->gameInformationService->getGameInformationForSteamAppId($steamAppId);

        $game = new Game($steamAppId);
        $game->setName(Game::NAME_FAILED);
        $game->setHeaderImagePath(Game::IMAGE_FAILED);

        if (!empty($gameInformation)) {
            $game->setName($gameInformation['name']);
            $game->setHeaderImagePath($gameInformation['header_image']);
        }

        try {
            $this->gameRepository->save($game);
        } catch (\Doctrine\ORM\ORMException $exception) {

        }
    }
}
