<?php

namespace App\Service\Steam;

use App\Repository\GameRepository;

class AllGamesService
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * AllGamesService constructor.
     * @param GameRepository $gameRepository
     * @param GameService $gameService
     */
    public function __construct(GameRepository $gameRepository, GameService $gameService)
    {
        $this->gameRepository = $gameRepository;
        $this->gameService = $gameService;
    }

    public function update(): void
    {
        $games = $this->gameRepository->findAll();

        foreach ($games as $game) {
            $this->gameService->update($game);
        }
    }
}
