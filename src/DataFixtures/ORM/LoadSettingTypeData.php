<?php
namespace Werkint\Bundle\SettingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;
use Werkint\Bundle\SettingsBundle\Entity\SettingType;

/**
 * LoadSettingTypeData.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class LoadSettingTypeData extends AbstractFixture implements
    FixtureInterface,
    OrderedFixtureInterface,
    ContainerAwareInterface
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $drow) {
            $row = new SettingType();
            $row->setClass($drow['class'])
                ->setIsArray($drow['isArray'])
                ->setIsGroup($drow['isGroup']);
            $manager->persist($row);
            $this->addReference('setting-type-' . $row->getClass(), $row);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $path = $this->container->getParameter('werkint_settings_data');
        $path .= '/types.yml';
        $data = Yaml::parse(file_get_contents($path));

        return $data['root'];
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}