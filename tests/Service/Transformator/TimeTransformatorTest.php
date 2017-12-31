<?php

namespace tests\App\Service\Steam;

use App\Service\Transformator\TimeTransformator;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeTransformatorTest
 */
class TimeTransformatorTest extends TestCase
{

    public function setUp(): void
    {
    }

    public function testGetMinutes(): void
    {
        $timeTransformator = new TimeTransformator(1510);
        $this->assertEquals(10, $timeTransformator->getMinutes());
    }

    public function testGetHours(): void
    {
        $timeTransformator = new TimeTransformator(1510);
        $this->assertEquals(1, $timeTransformator->getHours());
    }

    public function testGetDays(): void
    {
        $timeTransformator = new TimeTransformator(1510);
        $this->assertEquals(1, $timeTransformator->getDays());
    }

    public function testGetTimeInMinutes(): void
    {
        $timeTransformator = new TimeTransformator(1510);
        $this->assertEquals(1510, $timeTransformator->getTimeInMinutes());
    }

    public function testGetTimeInHours(): void
    {
        $timeTransformator = new TimeTransformator(1510);
        $this->assertEquals(25, $timeTransformator->getTimeInHours());
    }

    public function testGeTimeIntDays(): void
    {
        $timeTransformator = new TimeTransformator(1510);
        $this->assertEquals(1, $timeTransformator->getTimeInDays());
    }
}
