<?php

namespace App\Listener;

use App\Entity\BasicInformationChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Service\Stats\BasicInformationService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class BasicInformationListener
 */
class BasicInformationListener
{
    /**
     * @var array
     */
    private $statProperties = [
        'addToOverallAchievements' => 'overallAchievements',
        'addToPlayerAchievements' => 'playerAchievements',
        'addToRecentlyPlayed' => 'recentlyPlayed',
        'addToTimePlayed' => 'timePlayed'
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

        if ($entity instanceof Game === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $basicInformationService = new BasicInformationService($overallGameStatsRepository);

        $basicInformationService->addGameInformation($entity);

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

        if ($entity instanceof Game === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $basicInformationService = new BasicInformationService($overallGameStatsRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $basicInformationChangeSet  = new BasicInformationChangeSet();
        foreach ($this->statProperties as $key => $statProperty) {
            if (array_key_exists($statProperty, $changeSet)) {
                $diff = $changeSet[$statProperty][1] - $changeSet[$statProperty][0];
                $basicInformationChangeSet->$key($diff);
            }
        }

        $basicInformationService->updateChangeSet($basicInformationChangeSet);

        return 'U';
    }
}
