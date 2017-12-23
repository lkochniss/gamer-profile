<?php

namespace App\Command\Steam;

/**
 * Class UpdateAllGamesCommand
 */
class UpdateAllGamesCommand extends AbstractSteamCommand
{
    protected function configure(): void
    {
        $this->setName('steam:update:all');
        $this->setDescription('Synchronizes local game information with steam');
    }

    protected function getMyGames(): array
    {
        return $this->getGamesOwnedService()->getAllMyGames();
    }
}
