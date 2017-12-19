<?php

namespace App\Service;

/**
 * Class ReportService
 */
class ReportService
{
    const NEW_GAME = 'Added %s new games';
    const UPDATED_GAME = 'Updated %s games';
    const FIND_GAME_ERROR = 'Error getting game information on %s games';

    /**
     * @var array
     */
    private $report;

    /**
     * ReportService constructor.
     */
    public function __construct()
    {
        $this->report = [];
    }

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
        if (array_key_exists($list, $this->report)){
            return $this->report[$list];
        }

        return [];
    }

}
