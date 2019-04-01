<?php

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 */
class UserProvider implements UserProviderInterface
{

    /**
     * @var AWSCognitoClient
     */
    private $cognitoClient;

    /**
     * UserProvider constructor.
     * @param AwsCognitoClient $cognitoClient
     */
    public function __construct(AwsCognitoClient $cognitoClient)
    {
        $this->cognitoClient = $cognitoClient;
    }

    /**
     * @param string $username
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username): UserInterface
    {
        $result = $this->cognitoClient->findByUsername($username);

        if (count($result['Users']) === 0) {
            throw new UsernameNotFoundException();
        }

        return $this->setupUser($result['Users'][0]);
    }

    /**
     * @return User[]
     */
    public function loadUsers(): array
    {
        $users = [];
        foreach ($this->cognitoClient->getAllUsers()['Users'] as $user) {
            $users[] = $this->setupUser($user);
        }

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
     * @param string $username
     * @param int $steamUserId
     */
    public function saveSteamUserId(string $username, int $steamUserId): void
    {
        $this->cognitoClient->setSteamUserId($username, $steamUserId);
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

    /**
     * @param array $result
     * @return UserInterface
     */
    private function setupUser(array $result): UserInterface
    {
        $user = new User();
        // var_dump($result['Attributes']);
        foreach ($result['Attributes'] as $attribute) {
            switch ($attribute['Name']) {
                case 'email':
                    $user->setEmail($attribute['Value']);
                    break;
                case 'custom:steamUserId':
                    $user->setSteamId($attribute['Value']);
                    break;
            };
        }

        return $user;
    }
}
