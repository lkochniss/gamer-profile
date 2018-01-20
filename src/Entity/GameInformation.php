<?php

namespace App\Entity;

/**
 * Class Game
 */
class GameInformation extends AbstractEntity
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $headerImagePath;

    /**
     * @var int
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * GameInformation constructor.
     * @param array $gameInformation
     */
    public function __construct(array $gameInformation)
    {
        $this->name = $gameInformation['name'];
        $this->headerImagePath = $gameInformation['header_image'];

        $price = array_key_exists('price_overview', $gameInformation) ?
            $gameInformation['price_overview']['final'] / 100 : 0;

        $currency = array_key_exists('price_overview', $gameInformation) ?
            $gameInformation['price_overview']['currency'] : getenv('%DEFAULT_CURRENCY%');

        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHeaderImagePath(): string
    {
        return $this->headerImagePath;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
