<?php

namespace App\Listener;

use App\Entity\Playtime;
use App\Entity\ChangeSet\PlaytimeChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Service\GameStats\OverallGameStatsService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class PlaytimeListener
 */
class PlaytimeListener
{
    /**
     * @var array
     */
    private $statProperties = [
        'setRecentPlaytime' => 'recentPlaytime',
        'setOverallPlaytime' => 'overallPlaytime'
    ];

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Playtime === false) {
            return;
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $overallGameStatsService = new OverallGameStatsService($overallGameStatsRepository);

        $overallGameStatsService->addPlaytime($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        /**
         * @var Game $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Playtime === false) {
            return;
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $overallGameStatsService = new OverallGameStatsService($overallGameStatsRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $playtimeChangeSet  = new PlaytimeChangeSet();
        $playtimeChangeSet->setUser($entity->getSteamUserId());
        foreach ($this->statProperties as $key => $statProperty) {
            if (array_key_exists($statProperty, $changeSet)) {
                $diff = $changeSet[$statProperty][1] - $changeSet[$statProperty][0];
                $playtimeChangeSet->$key($diff);
            }
        }

        $overallGameStatsService->updatePlaytimeWithChangeSet($playtimeChangeSet);
    }
}
