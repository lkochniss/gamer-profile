<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\GameSession;

/**
 * Class GameSessionRepository
 */
class GameSessionRepository extends AbstractRepository#
{
    /**
     * @param AbstractEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AbstractEntity $entity): void
    {
        $entity->setSlug($this->slugify(
            $entity->getCreatedAt()->format('d-m-y-').$entity->getGame()->getName()
        ));
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @return string
     */
    protected function getEntity(): string
    {
        return GameSession::class;
    }
}
