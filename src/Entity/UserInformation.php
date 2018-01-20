<?php

namespace App\Entity;

/**
 * Class Game
 */
class UserInformation extends AbstractEntity
{
    /**
     * @var int
     */
    private $timePlayed;

    /**
     * @var int
     */
    private $recentlyPlayed;

    /**
     * UserInformation constructor.
     * @param array $gameInformation
     */
    public function __construct(array $gameInformation)
    {
        $this->timePlayed = $gameInformation['playtime_forever'];

        $this->recentlyPlayed = array_key_exists(
            'playtime_2weeks',
            $gameInformation
        ) ? $gameInformation['playtime_2weeks'] : 0;
    }

    /**
     * @return int
     */
    public function getTimePlayed(): int
    {
        return $this->timePlayed;
    }

    /**
     * @return int
     */
    public function getRecentlyPlayed(): int
    {
        return $this->recentlyPlayed;
    }
}
