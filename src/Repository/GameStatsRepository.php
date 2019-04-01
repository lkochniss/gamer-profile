<?php

namespace App\Repository;

use App\Entity\GameStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class GameStatsRepository
 */
class GameStatsRepository extends ServiceEntityRepository
{
    /**
     * GameStatsRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameStats::class);
    }

    /**
     * @param GameStats $gameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(GameStats $gameStats): void
    {
        $this->getEntityManager()->persist($gameStats);
        $this->getEntityManager()->flush($gameStats);
    }
}
