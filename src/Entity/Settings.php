<?php

namespace App\Entity;

/**
 * Class Settings
 */
class Settings extends AbstractEntity
{
    const STEAM_API_KEY = 'steam_api_key';
    const STEAM_USER_ID = 'steam_user_id';
    const STEAM_USER_NAME = 'steam_user_name';
    const GA_TRACKING = 'ga_tracking';
    const DEFAULT_CURRENCY = 'default_currency';

    /**
     * @var string
     */
    private $settingsKey;
    /**
     * @var string
     */
    private $settingsValue;
    /**
     * Settings constructor.
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->settingsKey = $key;
        $this->slug = $key;
    }
    /**
     * @return string
     */
    public function getSettingsKey(): string
    {
        return $this->settingsKey;
    }
    /**
     * @return string
     */
    public function getSettingsValue(): ?string
    {
        return $this->stringTransform($this->settingsValue);
    }
    /**
     * @param null|string $settingsValue
     */
    public function setSettingsValue(?string $settingsValue): void
    {
        $this->settingsValue = $settingsValue;
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->stringTransform($this->settingsValue);
    }
}
