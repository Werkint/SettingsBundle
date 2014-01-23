<?php
namespace Werkint\Bundle\SettingsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SettingRepository.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SettingRepository extends EntityRepository implements
    SettingInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRootNodes()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.parent is null');
        return $qb
            ->getQuery()
            ->getResult();
    }

}