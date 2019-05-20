<?php

namespace App\Repository;

use App\Entity\GameStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
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
     * @param int $steamUserId
     * @return GameStats[]
     */
    public function getByRecentlyPlayed(int $steamUserId): array
    {
        $query = $this->createQueryBuilder('gameStats')
            ->innerJoin('gameStats.playtime', 'playtime')
            ->addSelect('playtime')
            ->andWhere('playtime.recentPlaytime > 0')
            ->andWhere('gameStats.steamUserId = :steamUserId')
            ->andWhere('gameStats.status = :statusOpen')
            ->orWhere('playtime.recentPlaytime > 0')
            ->andWhere('gameStats.steamUserId = :steamUserId')
            ->andWhere('gameStats.status = :statusPaused')
            ->setParameter('steamUserId', $steamUserId)
            ->setParameter('statusOpen', GameStats::OPEN)
            ->setParameter('statusPaused', GameStats::PAUSED)
            ->getQuery();
        return $query->getResult();
    }

    /**
     * @param int $steamUserId
     * @return GameStats[]
     */
    public function getByPlayingStatusWithoutRecentPlaytime(int $steamUserId): array
    {
        $query = $this->createQueryBuilder('gameStats')
            ->innerJoin('gameStats.playtime', 'playtime')
            ->addSelect('playtime')
            ->andWhere('playtime.recentPlaytime = 0')
            ->andWhere('gameStats.steamUserId = :steamUserId')
            ->andWhere('gameStats.status = :status')
            ->setParameter('steamUserId', $steamUserId)
            ->setParameter('status', GameStats::PLAYING)
            ->getQuery();
        return $query->getResult();
    }

    /**
     * @param GameStats $gameStats
     */
    public function save(GameStats $gameStats): void
    {
        try {
            $this->getEntityManager()->persist($gameStats);
            $this->getEntityManager()->flush($gameStats);
        } catch (ORMException $exception) {
        }
    }
}
