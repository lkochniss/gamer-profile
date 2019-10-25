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

        $gameInformation = $this->gameInformationService->getGameInformationEntityForSteamAppId($steamAppId);

        $game = new Game($steamAppId);
        $game->setName($gameInformation->getName());
        $game->setHeaderImagePath($gameInformation->getHeaderImagePath());
        $game->setCategories($gameInformation->getCategories());

        $this->gameRepository->save($game);
    }

    /**
     * @param Game $game
     */
    public function update(Game $game): void
    {
        $gameInformation = $this->gameInformationService->getGameInformationEntityForSteamAppId($game->getSteamAppId());

        if ($gameInformation->getName() !== Game::NAME_FAILED) {
            $game->setName($gameInformation->getName());
            $game->setHeaderImagePath($gameInformation->getHeaderImagePath());
            $game->setCategories($gameInformation->getCategories());
            $game->setModifiedAt();
        }

        $this->gameRepository->save($game);
    }

    public function updateFailed()
    {
        $failedGames = $this->gameRepository->findBy(['name' => Game::NAME_FAILED]);

        foreach ($failedGames as $failedGame) {
            $this->update($failedGame);
        }
    }

    public function updateOldest()
    {
        $oldGames = $this->gameRepository->getLeastUpdatedGames(40);

        foreach ($oldGames as $oldGame) {
            $this->update($oldGame);
        }
    }

    /**
     * @param string $steamAppId
     */
    public function updateGameBySteamAppId(string $steamAppId)
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if (!is_null($game)) {
            $this->update($game);
        }
    }
}
