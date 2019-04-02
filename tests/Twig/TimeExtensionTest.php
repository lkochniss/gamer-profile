<?php

namespace tests\App\Twig;

use App\Service\Util\TimeConverterUtil;
use App\Twig\TimeExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeExtensionTest
 */
class TimeExtensionTest extends TestCase
{
    public function testConvertRecentTimeShouldCallTimeConverterService()
    {
        $inputTime = 10;

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertRecentTime')
            ->with($inputTime);

        $timeExtension = new TimeExtension($serviceMock);

        $timeExtension->convertRecentTime($inputTime);
    }

    public function testConvertRecentTimeShouldReturnTimeConverterServiceValue()
    {
        $inputTime = 10;
        $expectedString = 'returned time';

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertRecentTime')
            ->with($inputTime)
            ->willReturn($expectedString);

        $timeExtension = new TimeExtension($serviceMock);

        $this->assertEquals($expectedString, $timeExtension->convertRecentTime($inputTime));
    }

    public function testConvertOverallTimeShouldCallTimeConverterService()
    {
        $inputTime = 10;

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertOverallTime')
            ->with($inputTime);

        $timeExtension = new TimeExtension($serviceMock);

        $timeExtension->convertOverallTime($inputTime);
    }

    public function testConvertOverallTimeShouldReturnTimeConverterServiceValue()
    {
        $inputTime = 10;
        $expectedString = 'returned time';

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertOverallTime')
            ->with($inputTime)
            ->willReturn($expectedString);

        $timeExtension = new TimeExtension($serviceMock);

        $this->assertEquals($expectedString, $timeExtension->convertOverallTime($inputTime));
    }
}
