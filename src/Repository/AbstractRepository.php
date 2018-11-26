<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository extends ServiceEntityRepository
{

    /**
     * AbstractRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, $this->getEntity());
    }

    /**
     * @param AbstractEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    abstract public function save(AbstractEntity $entity): void;

    /**
     * @return string
     */
    abstract protected function getEntity(): string;
}
