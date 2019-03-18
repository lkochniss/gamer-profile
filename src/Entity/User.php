<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 */
class User implements UserInterface
{
    /**
     * @var
     */
    private $id;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSteamId(): int
    {
        return $this->steamId;
    }

    public function __toString(): string
    {
        return strval($this->getSteamId());
    }

    public function getUsername(): string
    {
        return strval($this->getSteamId());
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        if ($this->getSteamId() === 76561198045607524) {
            return  ['ROLE_ADMIN'];
        }

        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return '';
    }
}
