<?php

namespace App\Command;

use App\Entity\Achievement;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\GameStats;
use App\Entity\OverallGameStats;
use App\Entity\Playtime;
use App\Entity\PlaytimePerMonth;
use App\Repository\AchievementRepository;
use App\Repository\GameSessionRepository;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\GameStatsRepository;
use App\Repository\OverallGameStatsRepository;
use App\Repository\PlaytimePerMonthRepository;
use App\Repository\PlaytimeRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateWithSteamUserIdCommand
 */
class UpdateWithSteamUserIdCommand extends Command
{

    /**
     * @var AchievementRepository
     */
    private $achievementRepository;

    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * @var GameSessionsPerMonthRepository
     */
    private $gameSessionsPerMonthRepository;

    /**
     * @var GameStatsRepository
     */
    private $gameStatsRepository;

    /**
     * @var OverallGameStatsRepository
     */
    private $overallGameStatsRepository;

    /**
     * @var PlaytimeRepository
     */
    private $playtimeRepository;

    /**
     * @var PlaytimePerMonthRepository
     */
    private $playtimePerMonthRepository;

    protected function configure(): void
    {
        $this->setName('update:with:userId');
        $this->setDescription('Add a steamUserId to each entity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $achievements = $this->achievementRepository->findAll();

        /**
         * @var Achievement $achievement
         */
        foreach ($achievements as $achievement) {
            $achievement->setSteamUserID($achievement->getUser()->getSteamId());
            $this->achievementRepository->save($achievement);
        }

        $gameSessions = $this->gameSessionRepository->findAll();

        /**
         * @var GameSession $gameSession
         */
        foreach ($gameSessions as $gameSession) {
            $gameSession->setSteamUserID($gameSession->getUser()->getSteamId());
            $this->gameSessionRepository->save($gameSession);
        }

        $gameSessionsPerMonth = $this->gameSessionsPerMonthRepository->findAll();

        /**
         * @var GameSessionsPerMonth $item
         */
        foreach ($gameSessionsPerMonth as $item) {
            $item->setSteamUserID($item->getUser()->getSteamId());
            $this->gameSessionsPerMonthRepository->save($item);
        }

        $gameStats = $this->gameStatsRepository->findAll();

        /**
         * @var GameStats $gameStat
         */
        foreach ($gameStats as $gameStat) {
            $gameStat->setSteamUserID($gameStat->getUser()->getSteamId());
            $this->gameStatsRepository->save($gameStat);
        }

        $overallGameStats = $this->overallGameStatsRepository->findAll();

        /**
         * @var OverallGameStats $overallGameStat
         */
        foreach ($overallGameStats as $overallGameStat) {
            $overallGameStat->setSteamUserID($overallGameStat->getUser()->getSteamId());
            $this->overallGameStatsRepository->save($overallGameStat);
        }

        $playtimes = $this->playtimeRepository->findAll();

        /**
         * @var Playtime $playtime
         */
        foreach ($playtimes as $playtime) {
            $playtime->setSteamUserID($playtime->getUser()->getSteamId());
            $this->playtimeRepository->save($playtime);
        }

        $playtimesPerMonth = $this->playtimePerMonthRepository->findAll();

        /**
         * @var PlaytimePerMonth $item
         */
        foreach ($playtimesPerMonth as $item) {
            $item->setSteamUserID($item->getUser()->getSteamId());
            $this->playtimePerMonthRepository->save($item);
        }
    }
}
