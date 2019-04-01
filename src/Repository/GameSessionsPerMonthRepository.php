<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\GameSessionsPerMonth;
use App\Entity\User;

/**
 * Class GameSessionsPerMonthRepository
 */
class GameSessionsPerMonthRepository extends AbstractRepository
{
    /**
     * @param \DateTime $month
     * @param string $steamUserId
     * @return array|null
     */
    public function findByMonth(\DateTime $month, string $steamUserId): ?array
    {

        $query = $this->createQueryBuilder('game_sessions_per_month')
            ->where('game_sessions_per_month.month = :month')
            ->andWhere('game_sessions_per_month.steamUserId = :steamUserId')
            ->setParameter('month', $month)
            ->setParameter('steamUserId', $steamUserId)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $year
     * @param string $steamUserId
     * @return array|null
     */
    public function findByYear(int $year, string $steamUserId): ?array
    {
        $start = new \DateTime('first day of January ' . $year . ' 00:00:00');
        $end = new \DateTime('last day of December ' . $year);

        $query = $this->createQueryBuilder('game_sessions_per_month')
            ->where('game_sessions_per_month.month >= :start')
            ->andWhere('game_sessions_per_month.month < :end')
            ->andWhere('game_sessions_per_month.steamUserId = :steamUserId')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('steamUserId', $steamUserId)
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
