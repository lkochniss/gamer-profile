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

    /**
     * @return array|null
     */
    public function findForLastDays(): ?array
    {
        $start = new \DateTime('-15 day');
        $end = new \DateTime();
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return array|null
     */
    public function findForThisYear(): ?array
    {
        $start = new \DateTime('first day of January');
        $end = new \DateTime('last day of December');
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
