<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;

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
     * OverallGameStatsService constructor.
     * @param GameRepository $gameRepository
     * @param PurchaseService $purchaseService
     * @param GameSessionRepository $gameSessionRepository
     */
    public function __construct(
        GameRepository $gameRepository,
        PurchaseService $purchaseService,
        GameSessionRepository $gameSessionRepository
    ) {
        $this->gameRepository = $gameRepository;
        $this->purchaseService = $purchaseService;
        $this->gameSessionRepository = $gameSessionRepository;
    }

    /**
     * @return OverallGameStats
     */
    public function getAggregatedStats(): OverallGameStats
    {
        $games = $this->gameRepository->findAll();

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

        $gameSessions = $this->gameSessionRepository->findAll();
        $gameSessionsPerMonth = [];
        $gameSessionPlaytimePerMonth = [];

        /**
         * @var GameSession $gameSession
         */
        foreach ($gameSessions as $gameSession) {
            $yearAndMonthKey = $gameSession->getCreatedAt()->format('y-m');

            if (!key_exists($yearAndMonthKey, $gameSessionsPerMonth)) {
                $gameSessionsPerMonth[$yearAndMonthKey] = 0;
                $gameSessionPlaytimePerMonth[$yearAndMonthKey] = 0;
            }

            $gameSessionsPerMonth[$yearAndMonthKey]++;
            $gameSessionPlaytimePerMonth[$yearAndMonthKey] += $gameSession->getDuration();
        }

        $overallGameStats->setGameSessionsPerMonth($gameSessionsPerMonth);
        $overallGameStats->setGameSessionPlaytimePerMonth($gameSessionPlaytimePerMonth);

        return $overallGameStats;
    }

    /**
     * @return array
     */
    public function getMostPlayedGamePerMonth(): array
    {
        $bestGamePerMonth = [];

        $gameSessions = $this->gameSessionRepository->findAll();

        $gameSessionsPerMonthAndGame = [];
        /**
         * @var GameSession $gameSession
         */
        foreach ($gameSessions as $gameSession) {
            $yearAndMonthKey = $gameSession->getCreatedAt()->format('y-m');
            if (!key_exists($yearAndMonthKey, $gameSessionsPerMonthAndGame)) {
                $gameSessionsPerMonthAndGame[$yearAndMonthKey] = [];
            }

            $slug = $gameSession->getGame()->getSlug();
            if (!key_exists($slug, $gameSessionsPerMonthAndGame[$yearAndMonthKey])) {
                $gameSessionsPerMonthAndGame[$yearAndMonthKey][$slug] = [
                    'playTime' => 0,
                    'game' => $gameSession->getGame()
                ];
            }

            $gameSessionsPerMonthAndGame[$yearAndMonthKey][$slug]['playTime'] += $gameSession->getDuration();
        }

        foreach ($gameSessionsPerMonthAndGame as $yearAndMonthKey => $gamesWithDuration) {
            usort($gamesWithDuration, function ($gameA, $gameB) {
                return $gameA['playTime'] > $gameB['playTime'] ? -1 : 1;
            });

            $bestGame = array_shift($gamesWithDuration);
            $bestGamePerMonth[$yearAndMonthKey] = $bestGame['game'];
        }

        return $bestGamePerMonth;
    }
}
