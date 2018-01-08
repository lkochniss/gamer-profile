<?php

namespace App\Repository;

use App\Entity\GameSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class GameSessionRepository
 */
class GameSessionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameSession::class);
    }

    /**
     * @param GameSession $gameSession
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(GameSession $gameSession): void
    {
        $this->getEntityManager()->persist($gameSession);
        $this->getEntityManager()->flush($gameSession);
    }
}
