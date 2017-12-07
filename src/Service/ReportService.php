<?php

namespace App\Service;

/**
 * Class ReportService
 */
class ReportService
{
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

    public function addEntryToList($entry, $list)
    {
        if (!array_key_exists($list, $this->report)){
            $this->report[$list] = [];
        }
        $this->report[$list][] = $entry;

        return [$list => count($this->report[$list])];
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
