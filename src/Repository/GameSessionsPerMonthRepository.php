<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\GameSessionsPerMonth;

/**
 * Class GameSessionsPerMonthRepository
 */
class GameSessionsPerMonthRepository extends AbstractRepository#
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
     * @return string
     */
    protected function getEntity(): string
    {
        return GameSessionsPerMonth::class;
    }
}
