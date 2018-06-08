<?php

namespace App\Command;

use App\Service\Entity\CreateNewGameService;
use App\Service\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateNewGamesCommand
 */
class CreateNewGamesCommand extends ContainerAwareCommand
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var CreateNewGameService
     */
    private $createNewGameService;

    /**
     * CreateNewGamesCommand constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param CreateNewGameService $createNewGameService
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        CreateNewGameService $createNewGameService
    ) {
        parent::__construct();

        $this->gameUserInformationService = $gameUserInformationService;
        $this->createNewGameService = $createNewGameService;
    }

    protected function configure(): void
    {
        $this->setName('steam:create:new');
        $this->setDescription('Creates new games based on steam');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['', 'Starting:']);
        $mySteamGames = $this->gameUserInformationService->getAllGames();

        foreach ($mySteamGames as $mySteamGame) {
            $status = $this->createNewGameService->createGameIfNotExist($mySteamGame['appid']);
            $output->write($status);
        }

        $output->writeln(['','','Summary:']);
        $status = $this->createNewGameService->getSummary();
        foreach ($status as $key => $value) {
            $output->writeln('- ' . sprintf($key, $value));
        }
    }
}