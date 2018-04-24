<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\PlaytimePerMonth;

/**
 * Class PlaytimePerMonthRepository
 */
class PlaytimePerMonthRepository extends AbstractRepository#
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
        return PlaytimePerMonth::class;
    }
}
