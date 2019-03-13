<?php

namespace App\Command;

use App\Entity\GameSession;
use App\Repository\GameSessionRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TransformCreatedAtToDayCommand
 */
class TransformCreatedAtToDayCommand extends ContainerAwareCommand
{
    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * TransformCreatedAtToDayCommand constructor.
     * @param GameSessionRepository $gameSessionRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(GameSessionRepository $gameSessionRepository)
    {
        parent::__construct();

        $this->gameSessionRepository = $gameSessionRepository;
    }

    protected function configure(): void
    {
        $this->setName('gamesession:transform:day');
        $this->setDescription('transforms createdAt to day');
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
        $sessions = $this->gameSessionRepository->findAll();

        /**
         * @var GameSession $session
         */
        foreach ($sessions as $session) {
            $session->setDay($session->getCreatedAt()->setTime(00, 00, 00));
            $this->gameSessionRepository->save($session);
            $output->write('.');
        }
    }
}
