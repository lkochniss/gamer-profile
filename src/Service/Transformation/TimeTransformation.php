<?php

namespace App\Service\Transformation;

/**
 * Class TimeTransformation
 */
class TimeTransformation
{
    /**
     * @var int $timeInMinutes
     */
    private $timeInMinutes;

    /**
     * TimeTransformation constructor.
     *
     * @param int $timeInMinutes
     */
    public function __construct(int $timeInMinutes)
    {
        $this->timeInMinutes = $timeInMinutes;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        return $this->timeInMinutes % 60;
    }

    /**
     * @return int
     */
    public function getHours(): int
    {
        return intdiv($this->timeInMinutes % 1440, 60);
    }

    /**
     * @return int
     */
    public function getDays(): int
    {
        return intdiv($this->timeInMinutes, 1440);
    }

    /**
     * @return int
     */
    public function getTimeInMinutes(): int
    {
        return $this->timeInMinutes;
    }

    /**
     * @return int
     */
    public function getTimeInHours(): int
    {
        return intdiv($this->timeInMinutes, 60);
    }

    /**
     * @return int
     */
    public function getTimeInDays(): int
    {
        return intdiv($this->timeInMinutes, 1440);
    }
}
