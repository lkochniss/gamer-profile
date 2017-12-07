<?php

namespace tests\App\Service\Steam;

use App\Service\ReportService;
use PHPUnit\Framework\TestCase;

/**
 * Class ReportServiceTest
 */
class ReportServiceTest extends TestCase
{


    public function setUp(): void
    {

    }

    public function testAddEntryToList(): void
    {
        $reportService = new ReportService();
        $expectedReturn = ['entries' => 1];
        $actualReturn = $reportService->addEntryToList('new entry', 'entries');

        $this->assertEquals($expectedReturn, $actualReturn);
    }

}
