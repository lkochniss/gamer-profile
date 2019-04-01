<?php

namespace App\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\User;
use App\Repository\GameStatsRepository;

class CreateGameStatsService
{
    /**
     * @var AchievementService
     */
    private $achievementService;

    /**
     * @var PlaytimeService
     */
    private $playtimeService;

    /**
     * @var GameStatsRepository
     */
    private $gameStatsRepository;

    /**
     * CreateGameStats constructor.
     * @param AchievementService $achievementService
     * @param PlaytimeService $playtimeService
     * @param GameStatsRepository $gameStatsRepository
     */
    public function __construct(
        AchievementService $achievementService,
        PlaytimeService $playtimeService,
        GameStatsRepository $gameStatsRepository
    ) {
        $this->achievementService = $achievementService;
        $this->playtimeService = $playtimeService;
        $this->gameStatsRepository = $gameStatsRepository;
    }

    public function execute(User $user, Game $game): void
    {
        $gameStats = $this->gameStatsRepository->findOneBy(['steamUserId' => $user->getSteamId(), 'game' => $game]);
        if (!is_null($gameStats)) {
            return;
        }

        $achievement = $this->achievementService->create($user, $game);
        $playtime = $this->playtimeService->create($user, $game);

        $gameStats = new GameStats($user, $game, $achievement, $playtime);

        try {
            $this->gameStatsRepository->save($gameStats);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $exception) {
        }
    }
}
