<?php

namespace App\Entity;

/**
 * Class User
 */
class User extends AbstractEntity
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @var int
     */
    private $steamId;

    /**
     * User constructor.
     * @param int $steamId
     */
    public function __construct(int $steamId)
    {
        $this->steamId = $steamId;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return int
     */
    public function getSteamId(): int
    {
        return $this->steamId;
    }

    public function __toString()
    {
        return strval($this->getSteamId());
    }
}
