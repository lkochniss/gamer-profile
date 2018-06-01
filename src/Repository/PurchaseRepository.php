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
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @return array|null
     */
    public function findForLastTwelveMonth(): ?array
    {
        $start = new \DateTime('last day of this month last year');
        $end = new \DateTime('last day of this month');
        $query = $this->createQueryBuilder('purchase')
            ->where('purchase.boughtAt > :start')
            ->andWhere('purchase.boughtAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return string
     */
    protected function getEntity(): string
    {
        return Purchase::class;
    }
}
