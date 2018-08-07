<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\Playtime;
use App\Entity\User;

/**
 * Class PlaytimeRepository
 */
class PlaytimeRepository extends AbstractRepository
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
     * @return Playtime[]
     */
    public function getRecentPlaytime(User $user): array
    {
        $query = $this->createQueryBuilder('playtime')
            ->where('playtime.recentPlaytime > 0')
            ->andWhere('playtime.user = :user')
            ->setParameter('user', $user)
            ->orderBy('playtime.recentPlaytime', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $number
     * @param User $user
     * @return Playtime[]
     */
    public function getTopPlaytime(int $number, User $user): array
    {
        $query = $this->createQueryBuilder('playtime')
            ->where('playtime.overallPlaytime > 0')
            ->andWhere('playtime.user = :user')
            ->setParameter('user', $user)
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
