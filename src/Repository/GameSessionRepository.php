<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\GameSession;
use Doctrine\ORM\ORMException;

/**
 * Class GameSessionRepository
 */
class GameSessionRepository extends AbstractRepository
{
    /**
     * @param AbstractEntity $entity
     */
    public function save(AbstractEntity $entity): void
    {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush($entity);
        } catch (ORMException $exception) {
        }
    }

    /**
     * @param string $steamUserId
     * @return array|null
     */
    public function findForLastDays(string $steamUserId): ?array
    {
        $start = new \DateTime('-15 day');
        $end = new \DateTime();
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->andWhere('game_session.steamUserId = :steamUserId')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('steamUserId', $steamUserId)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param string $steamUserId
     * @return array|null
     */
    public function findForThisMonth(string $steamUserId): ?array
    {
        $start = new \DateTime('first day of this month');
        $end = new \DateTime('last day of this month');
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->andWhere('game_session.steamUserId = :steamUserId')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('steamUserId', $steamUserId)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param string $steamUserId
     * @return array|null
     */
    public function findForThisYear(string $steamUserId): ?array
    {
        $start = new \DateTime('first day of January');
        $end = new \DateTime('last day of December');
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->andWhere('game_session.steamUserId = :steamUserId')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('steamUserId', $steamUserId)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $year
     * @param string $steamUserId
     * @return array|null
     */
    public function findForYear(int $year, string $steamUserId): ?array
    {
        $start = new \DateTime('first day of January ' . $year);
        $end = new \DateTime('last day of December ' . $year);
        $query = $this->createQueryBuilder('game_session')
            ->where('game_session.createdAt > :start')
            ->andWhere('game_session.createdAt < :end')
            ->andWhere('game_session.steamUserId = :steamUserId')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('steamUserId', $steamUserId)
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
