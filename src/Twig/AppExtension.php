<?php

namespace App\Twig;

use App\Service\Transformator\TimeTransformator;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension
 */
class AppExtension extends AbstractExtension
{
    private $translator;

    /**
     * AppExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('convertOverallTime', [$this, 'convertOverallTime']),
            new TwigFilter('convertRecentTime', [$this, 'convertRecentTime']),
        ];
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertRecentTime(int $value): string
    {
        $time = new TimeTransformator($value);

        $hours = $this->translator->transChoice(
            '{0}%count% hours|{1}1 hour|]1,Inf[%count% hours',
            $time->getTimeInHours(),
            [],
            'time'
        );

        $minutes = $this->translator->transChoice(
            '{0}%count% minutes|{1}1 minute|]1,Inf[%count% minutes',
            $time->getMinutes(),
            [],
            'time'
        );

        return sprintf(
            '%s and %s',
            $hours,
            $minutes
        );
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertOverallTime(int $value): string
    {
        $time = new TimeTransformator($value);

        $days = $this->translator->transChoice(
            '{0}%count% days|{1}1 day|]1,Inf[%count% days',
            $time->getDays(),
            [],
            'time'
        );

        $hours = $this->translator->transChoice(
            '{0}%count% hours|{1}1 hour|]1,Inf[%count% hours',
            $time->getHours(),
            [],
            'time'
        );

        $minutes = $this->translator->transChoice(
            '{0}%count% minutes|{1}1 minute|]1,Inf[%count% minutes',
            $time->getMinutes(),
            [],
            'time'
        );

        return sprintf(
            '%s, %s and %s',
            $days,
            $hours,
            $minutes
        );
    }
}
