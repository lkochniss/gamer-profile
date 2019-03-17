<?php

namespace App\Listener;

use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\OverallGameStats;
use App\Repository\GameSessionsPerMonthRepository;
use App\Service\GameStats\GameSessionsPerMonthService;
use App\Service\GameStats\OverallGameStatsService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GameSessionListener
 */
class GameSessionListener
{
    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameSession === false) {
            return;
        }

        $gameSessionsPerMonthRepository = $args->getEntityManager()->getRepository(
            GameSessionsPerMonthRepository::class
        );
        $gameSessionsPerMonthService = new GameSessionsPerMonthService($gameSessionsPerMonthRepository);
        $gameSessionsPerMonthService->addGameSession($entity);

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);

        $overallGameStatsService = new OverallGameStatsService(
            $overallGameStatsRepository
        );

        $overallGameStatsService->addSession($entity->getUser());
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameSession === false) {
            return;
        }

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $gameSessionsPerMonthRepository = $args->getEntityManager()->getRepository(
            GameSessionsPerMonthRepository::class
        );
        $gameSessionsPerMonthService = new GameSessionsPerMonthService($gameSessionsPerMonthRepository);

        if (array_key_exists('duration', $changeSet)) {
            $diff = $changeSet['duration'][1] - $changeSet['duration'][0];
            $gameSessionsPerMonthService->updateGameSession($diff, $entity);
        }
    }
}
