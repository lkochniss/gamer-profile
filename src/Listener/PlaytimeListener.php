<?php

namespace App\Listener;

use App\Entity\Playtime;
use App\Entity\ChangeSet\PlaytimeChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Service\Stats\PlaytimeService;
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
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): string
    {
        $entity = $args->getEntity();

        if ($entity instanceof Playtime === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $playtimeService = new PlaytimeService($overallGameStatsRepository);

        $playtimeService->addNew($entity);

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
        /**
         * @var Game $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Playtime === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $playtimeService = new PlaytimeService($overallGameStatsRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $playtimeChangeSet  = new PlaytimeChangeSet();
        $playtimeChangeSet->setUser($entity->getUser());
        foreach ($this->statProperties as $key => $statProperty) {
            if (array_key_exists($statProperty, $changeSet)) {
                $diff = $changeSet[$statProperty][1] - $changeSet[$statProperty][0];
                $playtimeChangeSet->$key($diff);
            }
        }

        $playtimeService->updateChangeSet($playtimeChangeSet);

        return 'U';
    }
}
