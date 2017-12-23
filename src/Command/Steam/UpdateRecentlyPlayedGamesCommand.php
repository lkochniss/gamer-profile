<?php

namespace App\Command\Steam;

/**
 * Class UpdateRecentlyPlayedGamesCommand
 */
class UpdateRecentlyPlayedGamesCommand extends AbstractSteamCommand
{
    protected function configure(): void
    {
        $this->setName('steam:update:recent');
        $this->setDescription('Synchronizes local game information with recently played steam games');
    }

    protected function getMyGames(): array
    {
        return $this->getGamesOwnedService()->getMyRecentlyPlayedGames();
    }
}
