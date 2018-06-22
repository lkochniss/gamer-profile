<?php

namespace App\Entity;

/**
 * Class PlaytimePerMonth
 */
class PlaytimePerMonth extends AbstractEntity
{
    /**
     * @var \DateTime
     */
    private $month;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $sessions;

    /**
     * @var User
     */
    private $user;

    /**
     * PlaytimePerMonth constructor.
     * @param \DateTime $month
     */
    public function __construct(\DateTime $month)
    {
        $this->month = $month;
        $this->duration = 0;
        $this->sessions = 0;
    }

    /**
     * @return \DateTime
     */
    public function getMonth(): \DateTime
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $playTime
     */
    public function addToDuration(int $playTime): void
    {
        $this->duration += $playTime;
    }

    /**
     * @return int
     */
    public function getSessions(): int
    {
        return $this->sessions;
    }

    public function addSession(): void
    {
        $this->sessions++;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
