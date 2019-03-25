<?php

namespace App\Listener;

use App\Entity\GameStats;
use App\Entity\OverallGameStats;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GameStatsListener
 */
class GameStatsListener
{
    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameStats === false) {
            return;
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);

        $gameStats = $overallGameStatsRepository->findOneBy(['user' => $entity->getUser()]);

        if (is_null($gameStats)) {
            return;
        }

        $gameStats->setStatusOpen($gameStats->getStatusOpen() + 1);
        $gameStats->setNumberOfGames($gameStats->getNumberOfGames() + 1);

        $overallGameStatsRepository->save($gameStats);

        return;
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): string
    {
        /**
         * @var array
         */
        $getProperties = [
            'open' => 'getStatusOpen',
            'paused' => 'getStatusPaused',
            'playing' => 'getStatusPlaying',
            'finished' => 'getStatusFinished',
            'given_up' => 'getStatusGivenUp',
        ];

        /**
         * @var array
         */
        $setProperties = [
            'open' => 'setStatusOpen',
            'paused' => 'setStatusPaused',
            'playing' => 'setStatusPlaying',
            'finished' => 'setStatusFinished',
            'given_up' => 'setStatusGivenUp',
        ];
        $entity = $args->getEntity();

        if ($entity instanceof GameStats === false) {
            return 'S';
        }

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $gameStats = $overallGameStatsRepository->findOneBy(['user' => $entity->getUser()]);

        if (array_key_exists('status', $changeSet)) {
            // add one to the new status
            $getNew = $getProperties[$changeSet['status'][1]];
            $setNew = $setProperties[$changeSet['status'][1]];
            $gameStats->$setNew($gameStats->$getNew() + 1);

            // remove one from the old status
            $getOld = $getProperties[$changeSet['status'][0]];
            $setOld = $setProperties[$changeSet['status'][0]];
            $gameStats->$setOld($gameStats->$getOld() - 1);

            $overallGameStatsRepository->save($gameStats);
        }

        return 'U';
    }
}
