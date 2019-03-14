<?php

namespace App\Entity;

/**
 * Class Game
 */
class Game extends AbstractEntity
{
    const NAME_FAILED = 'unknown game';
    const IMAGE_FAILED = '';

    /**
     * @var int
     */
    private $steamAppId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $headerImagePath;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @SuppressWarnings(PHPMD.ShortVariableName)
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
     * @return string
     */
    public function getHeaderImagePath(): string
    {
        return $this->headerImagePath;
    }

    /**
     * @param string $headerImagePath
     */
    public function setHeaderImagePath(string $headerImagePath): void
    {
        $this->headerImagePath = $headerImagePath;
    }
}
