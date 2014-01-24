<?php
namespace Werkint\Bundle\SettingsBundle\Entity;

/**
 * SettingType.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SettingType
{
    public function __toString()
    {
        return $this->getClass();
    }

    /**
     * @var string
     */
    protected $class;

    /**
     * @var integer
     */
    protected $id;

    /**
     * Set class
     *
     * @param string $class
     * @return SettingType
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var boolean
     */
    protected $isGroup;

    /**
     * @param boolean $isArray
     * @return $this
     */
    public function setIsArray($isArray)
    {
        $this->isArray = $isArray;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsArray()
    {
        return $this->isArray;
    }

    /**
     * @param boolean $isGroup
     * @return $this
     */
    public function setIsGroup($isGroup)
    {
        $this->isGroup = $isGroup;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsGroup()
    {
        return $this->isGroup;
    }

    /**
     * @var boolean
     */
    protected $isArray;
}