<?php
namespace Werkint\Bundle\SettingsBundle\Entity;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * SettingInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface SettingInterface extends ObjectRepository
{
    /**
     * @return Setting[]
     */
    public function getRootNodes();
} 