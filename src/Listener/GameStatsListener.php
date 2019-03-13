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
     * @var array
     */
    private $getProperties = [
        'open' => 'getStatusOpen',
        'paused' => 'getStatusPaused',
        'playing' => 'getStatusPlaying',
        'finished' => 'getStatusFinished',
        'given_up' => 'getStatusGivenUp',
    ];

    /**
     * @var array
     */
    private $setProperties = [
        'open' => 'setStatusOpen',
        'paused' => 'setStatusPaused',
        'playing' => 'setStatusPlaying',
        'finished' => 'setStatusFinished',
        'given_up' => 'setStatusGivenUp',
    ];

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): string
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameStats === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);

        $gameStats = $overallGameStatsRepository->findOneBy(['user' => $entity->getUser()]);
        $gameStats->setStatusOpen($gameStats->getStatusOpen() + 1);
        $gameStats->setNumberOfGames($gameStats->getNumberOfGames() + 1);

        $overallGameStatsRepository->save($gameStats);

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

        if ($entity instanceof GameStats === false) {
            return 'S';
        }

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $gameStats = $overallGameStatsRepository->findOneBy(['user' => $entity->getUser()]);

        if (array_key_exists('status', $changeSet)) {

            // add one to the new status
            $gameStats->$this->setProperties[$changeSet['duration'][1]](
                $gameStats->$this->getProperties[$changeSet['duration'][1]] - 1
            );

            // remove one from the old status
            $gameStats->$this->setProperties[$changeSet['duration'][0]](
                $gameStats->$this->getProperties[$changeSet['duration'][0]] - 1
            );
        }

        return 'U';
    }
}
