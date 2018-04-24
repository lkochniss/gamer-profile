<?php

namespace App\Command;

use App\Service\GameSessionsPerMonthService;
use App\Service\OverallGameStatsService;
use App\Service\PlaytimePerMonthService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StatsMigrationCommand
 */
class StatsMigrationCommand extends ContainerAwareCommand
{

    /**
     * @var OverallGameStatsService
     */
    private $overallGameStatsService;

    /**
     * @var PlaytimePerMonthService
     */
    private $playtimePerMonthService;

    /**
     * @var GameSessionsPerMonthService
     */
    private $gameSessionsPerMonthService;

    /**
     * StatsMigrationCommand constructor.
     * @param OverallGameStatsService $overallGameStatsService
     * @param PlaytimePerMonthService $playtimePerMonthService
     * @param GameSessionsPerMonthService $gameSessionsPerMonthService
     */
    public function __construct(
        OverallGameStatsService $overallGameStatsService,
        PlaytimePerMonthService $playtimePerMonthService,
        GameSessionsPerMonthService $gameSessionsPerMonthService
    ) {
        parent::__construct();
        $this->overallGameStatsService = $overallGameStatsService;
        $this->playtimePerMonthService = $playtimePerMonthService;
        $this->gameSessionsPerMonthService = $gameSessionsPerMonthService;
    }

    protected function configure(): void
    {
        $this->setName('stats:migration:all');
        $this->setDescription('Migrates new stats entities');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->overallGameStatsService->generateOverallStats();
        $this->gameSessionsPerMonthService->generateGameSessionsPerMonth();
        $this->playtimePerMonthService->generatePlaytimePerMonth();
    }
}
