<?php

namespace App\Listener;

use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Repository\PlaytimePerMonthRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GameSessionListener
 */
class PlaytimePerMonthListener
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

        $playtimePerMonthRepository = $args->getEntityManager()->getRepository(PlaytimePerMonth::class);
        $playtimePerMonth = $this->getPlaytimePerMonth($playtimePerMonthRepository);

        $playtimePerMonth->addToDuration($entity->getDuration());
        $playtimePerMonth->addSession();

        $playtimePerMonthRepository->save($playtimePerMonth);
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

        $playtimePerMonthRepository = $args->getEntityManager()->getRepository(PlaytimePerMonth::class);
        $playtimePerMonth = $this->getPlaytimePerMonth($playtimePerMonthRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        if (array_key_exists('duration', $changeSet)) {
            $diff = $changeSet['duration'][1] - $changeSet['duration'][0];
            $playtimePerMonth->addToDuration($diff);
        }

        $playtimePerMonth->addToDuration($entity->getDuration());

        $playtimePerMonthRepository->save($playtimePerMonth);
    }

    /**
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     * @return PlaytimePerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getPlaytimePerMonth(PlaytimePerMonthRepository $playtimePerMonthRepository): PlaytimePerMonth
    {
        $month = new \DateTime('first day of this month');
        $playtimePerMonth = $playtimePerMonthRepository->findOneBy([
            'month' => $month
        ]);

        if (is_null($playtimePerMonth)) {
            $playtimePerMonth = new PlaytimePerMonth($month);
            $playtimePerMonthRepository->save($playtimePerMonth);
        }

        return $playtimePerMonth;
    }
}
