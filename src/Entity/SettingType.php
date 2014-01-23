<?php
namespace Werkint\Bundle\SettingsBundle\Entity;

/**
 * SettingType.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SettingType
{
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

    public function __toString()
    {
        return $this->getClass();
    }
}