<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\GameSession;

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

    public function findForLastDays()
    {
        $start = new \DateTime('-30 day');
        $end = new \DateTime('-1 day');
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
