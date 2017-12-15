<?php

namespace App\Entity;

/**
 * Class Game
 */
class Game
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $steamAppId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $timePlayed;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getSteamAppId(): int
    {
        return $this->steamAppId;
    }

    /**
     * @param int $steamAppId
     */
    public function setSteamAppId(int $steamAppId): void
    {
        $this->steamAppId = $steamAppId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getTimePlayed(): int
    {
        return $this->timePlayed;
    }

    /**
     * @param int $timePlayed
     */
    public function setTimePlayed(int $timePlayed): void
    {
        $this->timePlayed = $timePlayed;
    }
}
