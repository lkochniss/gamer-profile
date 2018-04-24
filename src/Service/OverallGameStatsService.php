<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\OverallGameStatsRepository;

/**
 * Class OverallStatsService
 */
class OverallGameStatsService
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var PurchaseService
     */
    private $purchaseService;

    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * @var OverallGameStatsRepository
     */
    private $overallGameStatsRepository;

    /**
     * OverallGameStatsService constructor.
     * @param GameRepository $gameRepository
     * @param PurchaseService $purchaseService
     * @param GameSessionRepository $gameSessionRepository
     * @param OverallGameStatsRepository $overallGameStatsRepository
     */
    public function __construct(
        GameRepository $gameRepository,
        PurchaseService $purchaseService,
        GameSessionRepository $gameSessionRepository,
        OverallGameStatsRepository $overallGameStatsRepository
    ) {
        $this->gameRepository = $gameRepository;
        $this->purchaseService = $purchaseService;
        $this->gameSessionRepository = $gameSessionRepository;
        $this->overallGameStatsRepository = $overallGameStatsRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateOverallStats(): void
    {
        $games = $this->gameRepository->findAll();

        $exitingGameStats = $this->overallGameStatsRepository->findOneByIdentifier(getenv('STEAM_USER_ID'));
        if (!is_null($exitingGameStats)) {
            return;
        }

        $overallGameStats = new OverallGameStats();

        /**
         * @var Game $game
         */
        foreach ($games as $game) {
            $overallGameStats->addToOverallAchievements($game->getOverallAchievements());
            $overallGameStats->addToPlayerAchievements($game->getPlayerAchievements());
            $overallGameStats->addToRecentlyPlayed($game->getRecentlyPlayed());
            $overallGameStats->addToTimePlayed($game->getTimePlayed());
            $overallGameStats->addToGameSessions(count($game->getGameSessions()));

            $purchaseMoney = $this->purchaseService->generateOverallCosts($game);
            $overallGameStats->addToInvestedMoney($this->purchaseService->transformPrice(
                $purchaseMoney,
                $game->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            if ($game->getTimePlayed() < 60) {
                $wastedMoney = $this->purchaseService->generateOverallCosts($game);
                $overallGameStats->addToWastedMoney($this->purchaseService->transformPrice(
                    $wastedMoney,
                    $game->getCurrency(),
                    $overallGameStats->getCurrency()
                ));
            }
        }

        $this->overallGameStatsRepository->save($overallGameStats);
    }
}
