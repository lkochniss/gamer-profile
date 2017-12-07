<?php

namespace tests\App\Service\Steam;

use App\Service\ReportService;
use PHPUnit\Framework\TestCase;

/**
 * Class ReportServiceTest
 */
class ReportServiceTest extends TestCase
{

    public function testAddOneEntryToOneList(): void
    {
        $reportService = new ReportService();
        $actualReturn = $reportService->addEntryToList('new entry', 'list one');

        $this->assertEquals(['list one' => 1], $actualReturn);
    }

    public function testAddMultipleEntriesToOneList(): void
    {
        $reportService = new ReportService();
        $reportService->addEntryToList('first entry', 'list one');
        $reportService->addEntryToList('second entry', 'list one');
        $reportService->addEntryToList('new entry', 'list one');
        $reportService->addEntryToList('new entry', 'list one');
        $actualReturn = $reportService->addEntryToList('last entry', 'list one');

        $this->assertEquals(['list one' => 5], $actualReturn);
    }

    public function testAddSingleEntriesToMultipleLists(): void
    {
        $reportService = new ReportService();
        $actualListOne = $reportService->addEntryToList('third entry', 'list one');
        $actualListTwo = $reportService->addEntryToList('first entry', 'list two');
        $actualListThree = $reportService->addEntryToList('second entry', 'list three');

        $this->assertEquals(['list one' => 1], $actualListOne);
        $this->assertEquals(['list two' => 1], $actualListTwo);
        $this->assertEquals(['list three' => 1], $actualListThree);
    }

    public function testAddMultipleEntriesToMultipleLists(): void
    {
        $reportService = new ReportService();
        $reportService->addEntryToList('first entry', 'list one');
        $reportService->addEntryToList('second entry', 'list one');
        $actualListTwo = $reportService->addEntryToList('first entry', 'list two');
        $actualListOne = $reportService->addEntryToList('third entry', 'list one');
        $reportService->addEntryToList('first entry', 'list three');
        $actualListThree = $reportService->addEntryToList('second entry', 'list three');

        $this->assertEquals(['list one' => 3], $actualListOne);
        $this->assertEquals(['list two' => 1], $actualListTwo);
        $this->assertEquals(['list three' => 2], $actualListThree);
    }

    public function testSummaryForOneEntryInOneList(): void
    {
        $reportService = new ReportService();
        $reportService->addEntryToList('new entry', 'list one');

        $summary = $reportService->getSummary();
        $this->assertEquals(['list one' => 1], $summary);
    }

    public function testSummaryForMultipleEntriesInOneList(): void
    {
        $reportService = new ReportService();
        $reportService->addEntryToList('first entry', 'list one');
        $reportService->addEntryToList('second entry', 'list one');
        $reportService->addEntryToList('new entry', 'list one');
        $reportService->addEntryToList('new entry', 'list one');
        $reportService->addEntryToList('last entry', 'list one');

        $summary = $reportService->getSummary();
        $this->assertEquals(['list one' => 5], $summary);
    }

    public function testSummaryForSingleEntriesInMultipleLists(): void
    {
        $reportService = new ReportService();
        $reportService->addEntryToList('third entry', 'list one');
        $reportService->addEntryToList('first entry', 'list two');
        $reportService->addEntryToList('second entry', 'list three');

        $summary = $reportService->getSummary();
        $this->assertEquals(['list one' => 1, 'list two' => 1, 'list three' => 1], $summary);
    }

    public function testSummaryForMultipleEntriesInMultipleLists(): void
    {
        $reportService = new ReportService();
        $reportService->addEntryToList('first entry', 'list one');
        $reportService->addEntryToList('second entry', 'list one');
        $reportService->addEntryToList('first entry', 'list two');
        $reportService->addEntryToList('third entry', 'list one');
        $reportService->addEntryToList('first entry', 'list three');
        $reportService->addEntryToList('second entry', 'list three');

        $summary = $reportService->getSummary();
        $this->assertEquals(['list one' => 3, 'list two' => 1, 'list three' => 2], $summary);
    }

    public function testGetDetailsForList()
    {
        $reportService = new ReportService();
        $reportService->addEntryToList('first entry', 'list one');
        $reportService->addEntryToList('second entry', 'list one');
        $reportService->addEntryToList('last entry', 'list one');

        $details = $reportService->getDetailsFor('list one');

        $this->assertEquals(['first entry', 'second entry', 'last entry'], $details);
    }
}
