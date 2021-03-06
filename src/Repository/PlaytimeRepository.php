<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\Playtime;
use Doctrine\ORM\ORMException;

/**
 * Class PlaytimeRepository
 */
class PlaytimeRepository extends AbstractRepository
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
     * @return array
     */
    public function getRecentPlaytime(string $steamUserId): array
    {
        $query = $this->createQueryBuilder('playtime')
            ->where('playtime.recentPlaytime > 0')
            ->andWhere('playtime.steamUserId = :steamUserId')
            ->setParameter('steamUserId', $steamUserId)
            ->orderBy('playtime.recentPlaytime', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $number
     * @param string $steamUserId
     * @return Playtime[]
     */
    public function getTopPlaytime(int $number, string $steamUserId): array
    {
        $query = $this->createQueryBuilder('playtime')
            ->where('playtime.overallPlaytime > 0')
            ->andWhere('playtime.steamUserId = :steamUserId')
            ->setParameter('steamUserId', $steamUserId)
            ->orderBy('playtime.overallPlaytime', 'DESC')
            ->setMaxResults($number)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return string
     */
    protected function getEntity(): string
    {
        return Playtime::class;
    }
}
