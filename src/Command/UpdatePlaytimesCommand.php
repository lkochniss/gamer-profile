<?php

namespace App\Command;

use App\Repository\PlaytimeRepository;
use App\Repository\UserRepository;
use App\Service\Entity\PlaytimeService;
use App\Service\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdatePlaytimesCommand
 */
class UpdatePlaytimesCommand extends ContainerAwareCommand
{
    /**
     * @var PlaytimeService
     */
    private $playtimeService;

    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PlaytimeRepository
     */
    private $playtimeRepository;

    /**
     * UpdatePlaytimeCommand constructor.
     * @param PlaytimeService $playtimeService
     * @param GameUserInformationService $gameUserInformationService
     * @param UserRepository $userRepository
     * @param PlaytimeRepository $playtimeRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        PlaytimeService $playtimeService,
        GameUserInformationService $gameUserInformationService,
        UserRepository $userRepository,
        PlaytimeRepository $playtimeRepository
    ) {
        parent::__construct();
        $this->playtimeService = $playtimeService;
        $this->gameUserInformationService = $gameUserInformationService;
        $this->userRepository = $userRepository;
        $this->playtimeRepository = $playtimeRepository;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:playtimes');
        $this->setDescription('Updates playtime for recently played games');
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

            $playtimes = $this->playtimeRepository->getRecentPlaytime($user);

            foreach ($playtimes as $playtime) {
                $playtime->setRecentPlaytime(0);
                $this->playtimeRepository->save($playtime);
            }

            $games = $this->gameUserInformationService->getRecentlyPlayedGames($user->getSteamid());

            foreach ($games as $game) {
                $playtime = $this->playtimeRepository->findOneBy(['user' => $user, 'game' => $game]);

                $status = 'F';
                if (!is_null($playtime)) {
                    $status = $this->playtimeService->update($playtime);
                }
                $output->write($status);
            }
        }
    }
}
