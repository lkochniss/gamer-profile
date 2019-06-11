<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\Achievement;
use Doctrine\ORM\ORMException;

/**
 * Class AchievementRepository
 */
class AchievementRepository extends AbstractRepository
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
        return Achievement::class;
    }
}
