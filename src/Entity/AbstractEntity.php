<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractEntity
 */
abstract class AbstractEntity
{
    /**
     * @var Integer
     */
    protected $id;
    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     */
    private $modifiedAt;
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return $this
     */
    public function setModifiedAt()
    {
        $this->modifiedAt = new \DateTime();
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }
    /**
     * @return $this
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $value
     * @return null|String
     */
    protected function stringTransform($value) : ?String
    {
        return $value ?: '';
    }
}