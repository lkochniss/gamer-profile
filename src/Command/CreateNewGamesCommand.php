<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\Entity\GameService;
use App\Service\Entity\GameStatsService;
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
     * @var GameService
     */
    private $gameService;

    /**
     * @var GameStatsService
     */
    private $gameStatsService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * CreateNewGamesCommand constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param GameService $gameService
     * @param GameStatsService $gameStatsService
     * @param UserRepository $userRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        GameService $gameService,
        GameStatsService $gameStatsService,
        UserRepository $userRepository
    ) {
        parent::__construct();

        $this->gameUserInformationService = $gameUserInformationService;
        $this->gameService = $gameService;
        $this->gameStatsService = $gameStatsService;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this->setName('steam:create:games');
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
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $output->writeln(['', 'Starting user: ' . $user->getSteamId()]);
            $games = $this->gameUserInformationService->getAllGames($user->getSteamId());
            foreach ($games as $game) {
                $game = $this->gameService->createGameIfNotExist($game['appid']);

                $status = 'F';
                if ($game) {
                    $gameStats = $this->gameStatsService->createGameStatsIfNotExist($game, $user);

                    $status = $gameStats ? 'N' : 'S';
                }

                $output->write($status);
            }
        }
    }
}
