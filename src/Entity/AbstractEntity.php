<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractEntity
 *
 * @SuppressWarnings(PHPMD.ShortVariableName)
 */
abstract class AbstractEntity
{
    /**
     * @var Integer
     *
     */
    protected $id;

    /**
     * @var String
     */
    protected $slug;
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
     * @return String
     */
    public function getSlug(): String
    {
        return $this->slug;
    }

    public function setSlug():void
    {
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

    /**
     * @param string $string
     * @return mixed|string
     */
    public function slugify(string $string): string
    {
        $string = strtolower($string);
        $string = str_replace('ä', 'ae', $string);
        $string = str_replace('ö', 'oe', $string);
        $string = str_replace('ü', 'ue', $string);
        $string = str_replace('ß', 'ss', $string);
        $string = str_replace('&', 'and', $string);
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);

        if (substr($string, -1) ==='-') {
            $string = substr($string, 0, -1);
        }

        return $string;
    }
}
