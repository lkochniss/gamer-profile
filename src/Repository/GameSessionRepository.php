<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\GameSession;
use App\Entity\User;

/**
 * Class GameSessionRepository
 */
class GameSessionRepository extends AbstractRepository#
{
    /**
     * @param AbstractEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AbstractEntity $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function findForLastDays(User $user): ?array
    {
        $start = new \DateTime('-15 day');
        $end = new \DateTime();
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->andWhere('game_session.user = :user')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function findForThisMonth(User $user): ?array
    {
        $start = new \DateTime('first day of this month');
        $end = new \DateTime('last day of this month');
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->andWhere('game_session.user = :user')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function findForThisYear(User $user): ?array
    {
        $start = new \DateTime('first day of January');
        $end = new \DateTime('last day of December');
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->andWhere('game_session.user = :user')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $year
     * @return array|null
     */
    public function findForYear(int $year): ?array
    {
        $start = new \DateTime('first day of January '. $year);
        $end = new \DateTime('last day of December '. $year);
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return string
     */
    protected function getEntity(): string
    {
        return GameSession::class;
    }
}
