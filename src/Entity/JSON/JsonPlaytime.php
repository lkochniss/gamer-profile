<?php

namespace App\Entity\JSON;

/**
 * Class JsonPlaytime
 */
class JsonPlaytime
{
    /**
     * @var int
     */
    private $overallPlaytime;

    /**
     * @var int
     */
    private $recentPlaytime;

    /**
     * UserInformation constructor.
     * @param array $gameInformation
     */
    public function __construct(array $gameInformation = [])
    {
        $this->overallPlaytime = array_key_exists(
            'playtime_forever',
            $gameInformation
        ) ? $gameInformation['playtime_forever'] : 0;

        $this->recentPlaytime = array_key_exists(
            'playtime_2weeks',
            $gameInformation
        ) ? $gameInformation['playtime_2weeks'] : 0;
    }

    /**
     * @return int
     */
    public function getOverallPlaytime(): int
    {
        return $this->overallPlaytime;
    }

    /**
     * @return int
     */
    public function getRecentPlaytime(): int
    {
        return $this->recentPlaytime;
    }
}
