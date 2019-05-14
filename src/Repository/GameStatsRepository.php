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
     * @param int $numberOfGames
     * @param int $steamUserId
     * @return GameStats[]
     */
    public function getMostPlayedGames(int $numberOfGames, int $steamUserId): array
    {
        $query = $this->createQueryBuilder('gameStats')
            ->innerJoin('gameStats.playtime', 'playtime')
            ->addSelect('playtime')
            ->andWhere('playtime.overallPlaytime > 0')
            ->andWhere('gameStats.steamUserId = :steamUserId')
            ->orderBy('playtime.overallPlaytime', 'DESC')
            ->setMaxResults($numberOfGames)
            ->setParameter('steamUserId', $steamUserId)
            ->getQuery();
        return $query->getResult();
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
