<?php

namespace App\Service\Steam;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Transformation\GameInformationService;

class GameService
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
    public function create(string $steamAppId): void
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

    /**
     * @param Game $game
     */
    public function update(Game $game): void
    {
        $gameInformation = $this->gameInformationService->getGameInformationForSteamAppId($game->getSteamAppId());

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

    public function updateFailed()
    {
        $failedGames = $this->gameRepository->findBy(['name' => Game::NAME_FAILED]);

        foreach ($failedGames as $failedGame) {
            $this->update($failedGame);
        }
    }
}
