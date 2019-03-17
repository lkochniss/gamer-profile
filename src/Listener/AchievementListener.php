<?php

namespace App\Listener;

use App\Entity\Achievement;
use App\Entity\ChangeSet\AchievementChangeSet;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Service\GameStats\OverallGameStatsService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class AchievementListener
 */
class AchievementListener
{
    /**
     * @var array
     */
    private $statProperties = [
        'setOverallAchievements' => 'overallAchievements',
        'setPlayerAchievements' => 'playerAchievements',
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

        if ($entity instanceof Achievement === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $achievementService = new OverallGameStatsService($overallGameStatsRepository);

        $achievementService->addAchievement($entity);

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

        if ($entity instanceof Achievement === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $achievementService = new OverallGameStatsService($overallGameStatsRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $achievementChangeSet = new AchievementChangeSet();
        $achievementChangeSet->setUser($entity->getUser());

        foreach ($this->statProperties as $key => $statProperty) {
            if (array_key_exists($statProperty, $changeSet)) {
                $diff = $changeSet[$statProperty][1] - $changeSet[$statProperty][0];
                $achievementChangeSet->$key($diff);
            }
        }

        $achievementService->updateAchievementWithChangeSet($achievementChangeSet);

        return 'U';
    }
}
