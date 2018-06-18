<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\Settings;

/**
 * Class SettingsRepository
 */
class SettingsRepository extends AbstractRepository
{

    /**
     * @param string $key
     * @return Settings
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function findOneByKeyOrCreate(string $key): Settings
    {
        /**
         * @var Settings
         */
        $settings = $this->findOneBy(['settingsKey' => $key]);
        if (is_null($settings)) {
            $settings = new Settings($key);
            $this->save($settings);
        }
        return $settings;
    }

    /**
     * @param AbstractEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AbstractEntity $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @return string
     */
    protected function getEntity(): string
    {
        return Settings::class;
    }
}
