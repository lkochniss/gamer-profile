<?php

namespace App\Listener;

use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Service\Stats\PlaytimePerMonthService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class PlaytimePerMonthListener
 */
class PlaytimePerMonthListener
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

        $playtimePerMonthRepository = $args->getEntityManager()->getRepository(PlaytimePerMonth::class);
        $playtimePerMonthService = new PlaytimePerMonthService($playtimePerMonthRepository);

        $playtimePerMonthService->addSession($entity);

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

        $playtimePerMonthRepository = $args->getEntityManager()->getRepository(PlaytimePerMonth::class);
        $playtimePerMonthService = new PlaytimePerMonthService($playtimePerMonthRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        if (array_key_exists('duration', $changeSet)) {
            $diff = $changeSet['duration'][1] - $changeSet['duration'][0];
            $playtimePerMonthService->updateSession($diff, $entity->getUser());
        }

        return 'U';
    }
}
