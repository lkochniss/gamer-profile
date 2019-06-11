<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\PlaytimePerMonth;
use Doctrine\ORM\ORMException;

/**
 * Class PlaytimePerMonthRepository
 */
class PlaytimePerMonthRepository extends AbstractRepository
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
     * @return string
     */
    protected function getEntity(): string
    {
        return PlaytimePerMonth::class;
    }
}
