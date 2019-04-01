<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 */
class User implements UserInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $steamId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * User constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $steamId
     */
    public function setSteamId(int $steamId): void
    {
        $this->steamId = $steamId;
    }

    /**
     * @return int
     */
    public function getSteamId(): int
    {
        return $this->steamId?: 0;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }


    public function __toString(): string
    {
        return strval($this->getSteamId());
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        return '';
    }
}
