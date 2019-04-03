<?php

namespace App\Service\Security\TestMocks;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class EmptyProvider
 *
 * The provider needs to be within src or the service can't autowire
 */
class EmptyProvider implements UserProviderInterface
{
    /**
     * @param string $username
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = new User();
        $user->setEmail($username);
        $user->setSteamId(76561198045607524);

        return $user;
    }

    /**
     * @return User[]
     */
    public function loadUsers(): array
    {
        $users = [];

        return $users;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf(
                    'Invalid user class "%s".',
                    get_class($user)
                )
            );
        }

        return $this->loadUserByUsername($user->getEmail());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
