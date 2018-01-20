<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\Purchase;

/**
 * Class PurchaseRepository
 */
class PurchaseRepository extends AbstractRepository
{
    /**
     * @param AbstractEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AbstractEntity $entity): void
    {
        $entity->setCreatedAt();
        $entity->setSlug($this->slugify(
            $entity->getCreatedAt()->format('d-m-y-').
            $entity->getGame()->getName().
            '-'.
            $entity->getType()
        ).
            '-'.
            count($entity->getGame()->getPurchases()));
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @return string
     */
    protected function getEntity(): string
    {
        return Purchase::class;
    }
}
