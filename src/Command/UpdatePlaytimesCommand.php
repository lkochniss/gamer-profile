<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdatePlaytimesCommand
 */
class UpdatePlaytimesCommand extends ContainerAwareCommand
{


    /**
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
    ) {
        parent::__construct();
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

    }
}
