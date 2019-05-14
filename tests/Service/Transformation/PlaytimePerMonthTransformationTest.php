<?php

namespace tests\App\Service\Steam;

use App\Entity\PlaytimePerMonth;
use App\Service\Transformation\PlaytimePerMonthTransformation;
use App\Service\Util\TimeConverterUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaytimePerMonthTransformationTest
 */
class PlaytimePerMonthTransformationTest extends TestCase
{
    public function testGetPlaytimeResponseShouldCallTimeTransformation(): void
    {
        $playtimePerMonth = new PlaytimePerMonth(new \DateTime(), 1);
        $playtimePerMonth->addToDuration(200);

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertOverallTime')
            ->with($playtimePerMonth->getDuration());

        $service = new PlaytimePerMonthTransformation($serviceMock);
        $service->getPlaytimeResponse($playtimePerMonth);
    }

    public function testGetPlaytimeResponseShouldGenerateResponse(): void
    {
        $playtimePerMonth = new PlaytimePerMonth(new \DateTime(), 1);
        $playtimePerMonth->addToDuration(200);

        $month = new \DateTime('first day of this month');
        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertOverallTime')
            ->with($playtimePerMonth->getDuration())
            ->willReturn(200);

        $expectedResponse = [
            'date' => $month->format('M Y'),
            'timeInMinutes' => $playtimePerMonth->getDuration(),
            'timeForTooltip' => $playtimePerMonth->getDuration()
        ];

        $service = new PlaytimePerMonthTransformation($serviceMock);

        $this->assertEquals($expectedResponse, $service->getPlaytimeResponse($playtimePerMonth));
    }

    public function testGetAveragePlaytimeResponseShouldCallTimeTransformation(): void
    {
        $month = new \DateTime('last day of may 2018');
        $playtimePerMonth = new PlaytimePerMonth($month, 1);
        $playtimePerMonth->addToDuration(200);

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertRecentTime')
            ->with(6);

        $service = new PlaytimePerMonthTransformation($serviceMock);
        $service->getAveragePlaytimeResponse($playtimePerMonth);
    }

    public function testGetAveragePlaytimeResponseShouldUseCurrentDay(): void
    {
        $month = new \DateTime();
        $playtimePerMonth = new PlaytimePerMonth($month, 1);
        $playtimePerMonth->addToDuration(200);

        $average = round($playtimePerMonth->getDuration() / $month->format('d'));

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertRecentTime')
            ->with($average);

        $service = new PlaytimePerMonthTransformation($serviceMock);
        $service->getAveragePlaytimeResponse($playtimePerMonth);
    }

    public function testGetAveragePlaytimeResponseShouldGenerateResponse(): void
    {
        $month = new \DateTime('last day of may 2018');
        $playtimePerMonth = new PlaytimePerMonth($month, 1);
        $playtimePerMonth->addToDuration(200);

        $average = round($playtimePerMonth->getDuration() / $month->format('d'));

        $serviceMock = $this->createMock(TimeConverterUtil::class);
        $serviceMock->expects($this->once())
            ->method('convertRecentTime')
            ->with($average)
            ->willReturn($average);


        $expectedResponse = [
            'date' => $month->format('M Y'),
            'timeInMinutes' => $average,
            'timeForTooltip' => $average
        ];

        $service = new PlaytimePerMonthTransformation($serviceMock);

        $this->assertEquals($expectedResponse, $service->getAveragePlaytimeResponse($playtimePerMonth));
    }
}
