<?php

namespace App\Service;

/**
 * Class ReportService
 */
class ReportService
{
    const NEW_GAME = 'Added %s new games';
    const UPDATED_GAME_INFORMATION = 'Updated %s games general information';
    const UPDATED_GAME_USER_INFORMATION = 'Updated %s games user information';
    const SKIPPED_GAME = 'Skipped %s already existing games';
    const FIND_GAME_INFORMATION_ERROR = 'Error getting game information on %s games';
    const FIND_USER_INFORMATION_ERROR = 'Error getting user information on %s games';
    const GAME_NOT_FOUND_ERROR = 'Error finding game with steamAppId %s';
    const CREATE_SESSION = 'Added session for game %s';

    /**
     * @var array
     */
    private $report = [];

    /**
     * @param string $entry
     * @param string $list
     *
     * @return int
     */
    public function addEntryToList(string $entry, string $list): int
    {
        if (!array_key_exists($list, $this->report)) {
            $this->report[$list] = [];
        }
        $this->report[$list][] = $entry;

        return count($this->report[$list]);
    }

    /**
     * @return array
     */
    public function getSummary(): array
    {
        $summary = [];
        foreach ($this->report as $list => $listArray) {
            $summary[$list] = count($listArray);
        }

        return $summary;
    }

    /**
     * @param string $list
     * @return array
     */
    public function getDetailsFor(string $list): array
    {
        if (array_key_exists($list, $this->report)) {
            return $this->report[$list];
        }

        return [];
    }
}
