<?php

namespace App\Service\Security;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Result;

/**
 * Class AwsCognitoClient
 */
class AwsCognitoClient
{
    /**
     * @var CognitoIdentityProviderClient
     */
    private $client;

    /**
     * @var string
     */
    private $poolId;

    /**
     * @var string
     */
    private $clientId;

    /**
     * AwsCognitoClient constructor.
     * @param string $poolId
     * @param string $clientId
     * @param string $region
     * @param string $version
     */
    public function __construct(
        string $poolId,
        string $clientId,
        string $region = 'eu-central-1',
        string $version = 'latest'
    ) {
        $this->client = new CognitoIdentityProviderClient([
            'region' => $region,
            'version' => $version
        ]);
        $this->poolId = $poolId;
        $this->clientId = $clientId;
    }

    /**
     * @param string $username
     *
     * @return Result
     */
    public function findByUsername(string $username): Result
    {
        return $this->client->listUsers([
            'UserPoolId' => $this->poolId,
            'Filter' => "email=\"" . $username . "\"",
        ]);
    }

    public function getAllUsers(): Result
    {
        return $this->client->listUsers([
            'UserPoolId' => $this->poolId
        ]);
    }


    /**
     * @param string $username
     * @param string $password
     *
     * @return Result
     */
    public function checkCredentials(string $username, string $password): Result
    {
        return $this->client->adminInitiateAuth([
            'UserPoolId' => $this->poolId,
            'ClientId' => $this->clientId,
            'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
            'AuthParameters' => [
                'USERNAME' => $username,
                'PASSWORD' => $password,
            ],
        ]);
    }

    /**
     * @param string $username
     *
     * @return Result
     */
    public function getRolesForUsername(string $username): Result
    {
        return $this->client->adminListGroupsForUser([
            'UserPoolId' => $this->poolId,
            'Username' => $username,
        ]);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return Result
     */
    public function signUp(string $username, string $password): Result
    {
        return $this->client->signUp([
            'ClientId' => $this->clientId,
            'Username' => $username,
            'Password' => $password,
            'UserAttributes' => [
                [
                    'Name' => 'email',
                    'Value' => $username,
                ],
            ],
        ]);
    }

    /**
     * @param string $username
     * @param string $steamUserId
     */
    public function setSteamUserId(string $username, string $steamUserId)
    {
        $this->client->adminUpdateUserAttributes([
            'UserPoolId' => $this->poolId,
            'Username' => $username,
            'UserAttributes' => [
                [
                    'Name' => 'custom:steamUserId',
                    'Value' => $steamUserId,
                ],
            ]
        ]);
    }

    /**
     * @param string $username
     *
     * @return Result
     */
    public function forgotPassword(string $username)
    {
        return $this->client->forgotPassword([
            'ClientId' => $this->clientId,
            'Username' => $username,
        ]);
    }
}
