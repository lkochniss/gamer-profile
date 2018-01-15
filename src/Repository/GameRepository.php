<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\Game;

/**
 * Class GameRepository
 */
class GameRepository extends AbstractRepository
{
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
    public function getRecentlyPlayedGames(): array
    {
        $query = $this->createQueryBuilder('game')
            ->where('game.recentlyPlayed > 0')
            ->orderBy('game.recentlyPlayed', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $number
     * @return array
     */
    public function getMostPlayedGames(int $number): array
    {
        $query = $this->createQueryBuilder('game')
            ->where('game.timePlayed > 0')
            ->orderBy('game.timePlayed', 'DESC')
            ->setMaxResults($number)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $number
     * @return array
     */
    public function getLeastUpdatedGames(int $number): array
    {
        $query = $this->createQueryBuilder('game')
            ->where('game.recentlyPlayed = 0')
            ->orderBy('game.modifiedAt', 'ASC')
            ->setMaxResults($number)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param AbstractEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AbstractEntity $entity): void
    {
        $entity->setSlug($this->slugify($entity->getName()));
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @return string
     */
    protected function getEntity(): string
    {
        return Game::class;
    }
}
