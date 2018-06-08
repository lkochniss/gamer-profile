<?php

namespace App\Twig;

use App\Service\Util\TimeConverterUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class TimeExtension
 */
class TimeExtension extends AbstractExtension
{
    /**
     * @var TimeConverterUtil
     */
    private $timeConverterService;
    /**
     * AppExtension constructor.
     * @param TimeConverterUtil $timeConverterService
     */
    public function __construct(TimeConverterUtil $timeConverterService)
    {
        $this->timeConverterService = $timeConverterService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('convert_overall_time', [$this, 'convertOverallTime']),
            new TwigFilter('convert_recent_time', [$this, 'convertRecentTime']),
        ];
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertRecentTime(int $value): string
    {
        return $this->timeConverterService->convertRecentTime($value);
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertOverallTime(int $value): string
    {
        return $this->timeConverterService->convertOverallTime($value);
    }
}
