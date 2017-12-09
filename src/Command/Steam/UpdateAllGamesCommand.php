<?php

namespace App\Command\Steam;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
 * Class UpdateAllGamesCommand
 */
class UpdateAllGamesCommand extends ContainerAwareCommand
{

    protected function configure(): void
    {
        $this->setName('gamerprofile:synchronize:steam');
        $this->setDescription('Synchronizes local game information with steam');
    }
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
