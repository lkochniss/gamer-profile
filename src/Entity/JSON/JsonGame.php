<?php

namespace App\Entity\JSON;

/**
 * Class Game
 */
class JsonGame
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
     * GameInformation constructor.
     * @param array $gameInformation
     */
    public function __construct(array $gameInformation)
    {
        $this->name = $gameInformation['name'];
        $this->headerImagePath = $gameInformation['header_image'];
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
}
