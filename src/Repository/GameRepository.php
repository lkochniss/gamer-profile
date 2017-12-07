<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameRepository
 */
class GameRepository extends EntityRepository
{
    public function findOneBySteamAppId($appId)
    {
       return $this->findOneBy(
           ['steamAppId' => $appId]
       );
    }

    public function save(Game $game)
    {
        $this->getEntityManager()->persist($game);
        $this->getEntityManager()->flush();
    }
}
