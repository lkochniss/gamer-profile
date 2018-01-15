<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Service\SlugifyService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @var SlugifyService
     */
    private $slugifyService;

    /**
     * BlogPostRepository constructor.
     * @param RegistryInterface $registry
     * @param SlugifyService $slugifyService
     */
    public function __construct(RegistryInterface $registry, SlugifyService $slugifyService)
    {
        parent::__construct($registry, $this->getEntity());
        $this->slugifyService = $slugifyService;
    }

    /**
     * @param AbstractEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    abstract public function save(AbstractEntity $entity): void;

    /**
     * @param String $string
     * @return string
     */
    protected function slugify(String $string): string
    {
        return $this->slugifyService->slugify($string);
    }

    /**
     * @return string
     */
    abstract protected function getEntity(): string;
}
