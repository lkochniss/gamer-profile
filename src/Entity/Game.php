<?php

namespace App\Entity;

/**
 * Class Game
 */
class Game extends AbstractEntity
{
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
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @var int
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->price = 0;
        $this->currency = 'USD';
    }

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

    /**
     * @return \DateTime
     */
    public function getReleaseDate(): \DateTime
    {
        return $this->releaseDate ? $this->releaseDate : new \DateTime();
    }

    /**
     * @param \DateTime|null $releaseDate
     */
    public function setReleaseDate(?\DateTime $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price / 100;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = intval($price * 100);
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return getenv('DEFAULT_CURRENCY');
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
