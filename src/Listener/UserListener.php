<?php

namespace App\Listener;

use App\Entity\User;
use App\Service\GameStats\CreateGameStatsForUsersGamesService;
use App\Service\Steam\GamesForUserService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class UserListener
 */
class UserListener
{
    /**
     * @var GamesForUserService
     */
    private $createGamesForUserService;

    /**
     * @var CreateGameStatsForUsersGamesService
     */
    private $createGameStatsForUsersGamesService;

    /**
     * UserListener constructor.
     * @param GamesForUserService $createGamesForUserService
     * @param CreateGameStatsForUsersGamesService $createGameStatsForUsersGamesService
     */
    public function __construct(GamesForUserService $createGamesForUserService, CreateGameStatsForUsersGamesService $createGameStatsForUsersGamesService)
    {
        $this->createGamesForUserService = $createGamesForUserService;
        $this->createGameStatsForUsersGamesService = $createGameStatsForUsersGamesService;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        /**
         * @var User $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof User === false) {
            return;
        }

        $this->createGamesForUserService->create($entity->getSteamId());
        $this->createGameStatsForUsersGamesService->execute($entity);
    }

}
