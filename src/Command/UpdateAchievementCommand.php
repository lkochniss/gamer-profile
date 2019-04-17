<?php

namespace App\Command;

use App\Service\GameStats\UpdateAchievementForAllUsersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateAchievementCommand
 */
class UpdateAchievementCommand extends Command
{
    /**
     * @var UpdateAchievementForAllUsersService
     */
    private $updateAchievementForAllUsersService;

    /**
     * UpdateAchievementCommand constructor.
     * @param UpdateAchievementForAllUsersService $updateAchievementForAllUsersService
     */
    public function __construct(UpdateAchievementForAllUsersService $updateAchievementForAllUsersService)
    {
        parent::__construct();
        $this->updateAchievementForAllUsersService = $updateAchievementForAllUsersService;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:achievement');
        $this->setDescription('Updates achievements for recently played games');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateAchievementForAllUsersService->recently();
        $this->updateAchievementForAllUsersService->noneExisting();
    }
}
