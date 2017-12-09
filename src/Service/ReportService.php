<?php

namespace App\Service;

/**
 * Class ReportService
 */
class ReportService
{
    const NEW_GAME = 'new game';
    const UPDATED_GAME = 'updated game';

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
        return $this->report[$list];
    }

}
