<?php

namespace App\Tests\Service\Security;

use App\Entity\User;
use App\Service\Security\AwsCognitoClient;
use App\Service\Security\UserProvider;
use Aws\Result;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProviderTest extends TestCase
{
    public function testLoadUserByUsernameShouldCallCognitoClientWithUsername(): void
    {
        $username = 'user1';
        $result = new Result(['Users' => []]);

        $this->expectException(UsernameNotFoundException::class);

        $clientMock = $this->createMock(AwsCognitoClient::class);
        $clientMock->expects($this->once())
            ->method('findByUsername')
            ->with($username)
            ->willReturn($result);

        $userProvider = new UserProvider($clientMock);
        $userProvider->loadUserByUsername($username);
    }

    public function testLoadUserByUsernameShouldReturnAUser(): void
    {
        $username = 'user1';
        $result = new Result(['Users' => [
            [
                'Attributes' => [
                    [
                        'Name' => 'email',
                        'Value' => 'user@example.com'

                    ],
                    [
                        'Name' => 'custom:steamUserId',
                        'Value' => '1'

                    ],
                ]
            ]
        ]]);

        $clientMock = $this->createMock(AwsCognitoClient::class);
        $clientMock->expects($this->once())
            ->method('findByUsername')
            ->with($username)
            ->willReturn($result);

        $userProvider = new UserProvider($clientMock);
        $actualUser = $userProvider->loadUserByUsername($username);

        $expectedUser = new User();
        $expectedUser->setEmail('user@example.com');
        $expectedUser->setSteamId(1);

        $this->assertEquals($expectedUser, $actualUser);
    }

    public function testLoadUsersShouldCallCognitoClientWithUsername(): void
    {
        $result = new Result(['Users' => []]);

        $clientMock = $this->createMock(AwsCognitoClient::class);
        $clientMock->expects($this->once())
            ->method('getAllUsers')
            ->willReturn($result);

        $userProvider = new UserProvider($clientMock);
        $userProvider->loadUsers();
    }

    public function testRefreshUserShouldCallCognitoClientWithUsername(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');

        $result = new Result(['Users' => []]);

        $this->expectException(UsernameNotFoundException::class);

        $clientMock = $this->createMock(AwsCognitoClient::class);
        $clientMock->expects($this->once())
            ->method('findByUsername')
            ->with($user->getEmail())
            ->willReturn($result);

        $userProvider = new UserProvider($clientMock);
        $userProvider->refreshUser($user);
    }

    public function testRefreshUserShouldThrowAnExceptionOnWrongClass(): void
    {
        $user = new \Symfony\Component\Security\Core\User\User('1', '2');

        $this->expectException(UnsupportedUserException::class);

        $clientMock = $this->createMock(AwsCognitoClient::class);

        $userProvider = new UserProvider($clientMock);
        $userProvider->refreshUser($user);
    }

    public function saveSteamUserIdShouldCallCognitoClientCorrectly()
    {
        $username = 'username';
        $steamUserId = 1;

        $clientMock = $this->createMock(AwsCognitoClient::class);
        $clientMock->expects($this->once())
            ->method('setSteamUserId')
            ->with(
                $username,
                $steamUserId
            );

        $userProvider = new UserProvider($clientMock);
        $userProvider->saveSteamUserId($username, $steamUserId);
    }
}
