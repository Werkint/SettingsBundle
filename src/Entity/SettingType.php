<?php
namespace Werkint\Bundle\SettingsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * SettingType.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SettingType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settings = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getClass();
    }

    // -- Entity ---------------------------------------

    /**
     * @var string
     */
    protected $class;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Collection
     */
    protected $settings;

    /**
     * Get setting
     *
     * @return Collection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Add setting
     *
     * @param Setting $settings
     * @return Setting
     */
    public function addSetting(Setting $settings)
    {
        $this->settings[] = $settings;

        return $this;
    }

    /**
     * Remove setting
     *
     * @param Setting $settings
     */
    public function removeSetting(Setting $settings)
    {
        $this->settings->removeElement($settings);
    }

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