<?php

namespace App\Service\Util;

use App\Service\Transformation\TimeTransformation;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TimeConverterUtil
 */
class TimeConverterUtil
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

    /**
     * @param int $value
     * @return string
     */
    public function convertRecentTime(int $value): string
    {
        $time = new TimeTransformation($value);

        $hours = $this->translator->transChoice(
            '{0}%count% hours|{1}1 hour|]1,Inf[%count% hours',
            $time->getTimeInHours(),
            [],
            'messages'
        );

        $minutes = $this->translator->transChoice(
            '{0}%count% minutes|{1}1 minute|]1,Inf[%count% minutes',
            $time->getMinutes(),
            [],
            'messages'
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
        $time = new TimeTransformation($value);

        $days = $this->translator->transChoice(
            'translate_days',
            $time->getDays(),
            [],
            'messages'
        );

        $hours = $this->translator->transChoice(
            'translate_hours',
            $time->getHours(),
            [],
            'messages'
        );

        $minutes = $this->translator->transChoice(
            'translate_minutes',
            $time->getMinutes(),
            [],
            'messages'
        );

        return sprintf(
            '%s, %s and %s',
            $days,
            $hours,
            $minutes
        );
    }
}
