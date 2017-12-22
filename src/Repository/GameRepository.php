<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class GameRepository
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @param $appId
     *
     * @return null|Game
     */
    public function findOneBySteamAppId($appId): ?Game
    {
        return $this->findOneBy(
            ['steamAppId' => $appId]
        );
    }

    /**
     * @return array
     */
    public function getRecentlyPlayedGames()
    {
        $query = $this->createQueryBuilder('game')
            ->where('game.recentlyPlayed > 0')
            ->orderBy('game.recentlyPlayed', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param Game $game
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Game $game): void
    {
        $this->getEntityManager()->persist($game);
        $this->getEntityManager()->flush($game);
    }
}
