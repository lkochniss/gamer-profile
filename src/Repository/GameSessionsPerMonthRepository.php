<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\GameSessionsPerMonth;

/**
 * Class GameSessionsPerMonthRepository
 */
class GameSessionsPerMonthRepository extends AbstractRepository
{
    /**
     * @param int $year
     * @return array|null
     */
    public function findByYear(int $year): ?array
    {
        $start = new \DateTime('first day of January ' . $year . ' 00:00:00');
        $end = new \DateTime('last day of December ' . $year);

        $query = $this->createQueryBuilder('game_sessions_per_month')
            ->where('game_sessions_per_month.month >= :start')
            ->andWhere('game_sessions_per_month.month < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery();

        return $query->getResult();
    }

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
     * @return string
     */
    protected function getEntity(): string
    {
        return GameSessionsPerMonth::class;
    }
}
