<?php

namespace tests\App\Service\Steam;

use App\Service\Transformation\TimeTransformation;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeTransformationTest
 */
class TimeTransformationTest extends TestCase
{

    public function setUp(): void
    {
    }

    public function testGetMinutes(): void
    {
        $TimeTransformation = new TimeTransformation(1510);
        $this->assertEquals(10, $TimeTransformation->getMinutes());
    }

    public function testGetHours(): void
    {
        $TimeTransformation = new TimeTransformation(1510);
        $this->assertEquals(1, $TimeTransformation->getHours());
    }

    public function testGetDays(): void
    {
        $TimeTransformation = new TimeTransformation(1510);
        $this->assertEquals(1, $TimeTransformation->getDays());
    }

    public function testGetTimeInMinutes(): void
    {
        $TimeTransformation = new TimeTransformation(1510);
        $this->assertEquals(1510, $TimeTransformation->getTimeInMinutes());
    }

    public function testGetTimeInHours(): void
    {
        $TimeTransformation = new TimeTransformation(1510);
        $this->assertEquals(25, $TimeTransformation->getTimeInHours());
    }

    public function testGeTimeIntDays(): void
    {
        $TimeTransformation = new TimeTransformation(1510);
        $this->assertEquals(1, $TimeTransformation->getTimeInDays());
    }
}
