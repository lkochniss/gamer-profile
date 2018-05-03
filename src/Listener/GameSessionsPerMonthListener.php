<?php

namespace App\Listener;

use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\OverallGameStats;
use App\Service\Stats\GameSessionsPerMonthService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GameSessionsPerMonthListener
 */
class GameSessionsPerMonthListener
{
    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): string
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameSession === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $gameSessionPerMonthRepository = $args->getEntityManager()->getRepository(GameSessionsPerMonth::class);

        $gameSessionsPerMonthService = new GameSessionsPerMonthService(
            $gameSessionPerMonthRepository,
            $overallGameStatsRepository
        );

        $gameSessionsPerMonthService->addGameSession($entity);

        return 'U';
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): string
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameSession === false) {
            return 'S';
        }

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $gameSessionPerMonthRepository = $args->getEntityManager()->getRepository(GameSessionsPerMonth::class);

        $gameSessionsPerMonthService = new GameSessionsPerMonthService(
            $gameSessionPerMonthRepository,
            $overallGameStatsRepository
        );

        if (array_key_exists('duration', $changeSet)) {
            $diff = $changeSet['duration'][1] - $changeSet['duration'][0];
            $gameSessionsPerMonthService->updateGameSession($diff, $entity);
        }

        return 'U';
    }
}
