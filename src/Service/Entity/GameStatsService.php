<?php

namespace App\Service\Entity;

use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\User;
use App\Repository\GameStatsRepository;

/**
 * Class CreateNewGameStatsService
 */
class GameStatsService
{
    /**
     * @var GameStatsRepository
     */
    private $gameStatsRepository;

    /**
     * @var AchievementService;
     */
    private $createAchievementService;

    /**
     * @var PlaytimeService
     */
    private $createPlaytimeService;

    /**
     * CreateNewGameStatsService constructor.
     * @param GameStatsRepository $gameStatsRepository
     * @param AchievementService $createAchievementService
     * @param PlaytimeService $createPlaytimeService
     */
    public function __construct(
        GameStatsRepository $gameStatsRepository,
        AchievementService $createAchievementService,
        PlaytimeService $createPlaytimeService
    ) {
        $this->gameStatsRepository = $gameStatsRepository;
        $this->createAchievementService = $createAchievementService;
        $this->createPlaytimeService = $createPlaytimeService;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return GameStats|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     */
    public function createGameStatsIfNotExist(Game $game, User $user): ?GameStats
    {
        $gameStats = $this->gameStatsRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (!is_null($gameStats)) {
            return null;
        }

        $achievement = $this->createAchievementService->createIfNotExists($game, $user);
        $playtime = $this->createPlaytimeService->createIfNotExists($game, $user);

        $gameStats = new GameStats($user, $game, $achievement, $playtime);
        $this->gameStatsRepository->save($gameStats);

        return $gameStats;
    }
}
