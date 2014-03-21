<?php
namespace Werkint\Bundle\SettingsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Setting.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Setting
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isEncrypted = false;

        $this->children = new ArrayCollection();
    }

    // -- Entity ---------------------------------------

    /**
     * @var string
     */
    protected $class;
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Collection
     */
    protected $children;

    /**
     * @var Setting
     */
    protected $parent;

    /**
     * @var SettingType
     */
    protected $type;

    /**
     * Set class
     *
     * @param string $class
     * @return Setting
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
     * Set title
     *
     * @param string $title
     * @return Setting
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param Setting $parent
     * @return Setting
     */
    public function setParent(Setting $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Setting
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set type
     *
     * @param SettingType $type
     * @return Setting
     */
    public function setType(SettingType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return SettingType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add children
     *
     * @param Setting $children
     * @return Setting
     */
    public function addChild(Setting $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Setting $children
     */
    public function removeChild(Setting $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * @var string
     */
    protected $environment;

    /**
     * @param string $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @var string
     */
    protected $parameter;


    /**
     * Set parameter
     *
     * @param string $parameter
     * @return Setting
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Get parameter
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @var boolean
     */
    protected $isEncrypted;


    /**
     * Set isEncrypted
     *
     * @param boolean $isEncrypted
     * @return Setting
     */
    public function setIsEncrypted($isEncrypted)
    {
        $this->isEncrypted = $isEncrypted;

        return $this;
    }

    /**
     * Get isEncrypted
     *
     * @return boolean
     */
    public function getIsEncrypted()
    {
        return $this->isEncrypted;
    }
}