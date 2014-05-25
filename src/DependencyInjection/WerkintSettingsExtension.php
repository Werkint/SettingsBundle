<?php
namespace Werkint\Bundle\SettingsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * WerkintSettingsExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSettingsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(
        array $configs,
        ContainerBuilder $container
    ) {
        $configDir = __DIR__ . '/../Resources/config';
        $container->setParameter(
            'werkint_settings_data',
            $configDir . '/data'
        );

        $processor = new Processor();
        $config = $processor->processConfiguration(
            $this->getConfiguration($configs, $container),
            $configs
        );
        $container->setParameter(
            $this->getAlias() . '_dir',
            $config['dir']
        );
        $container->setParameter(
            $this->getAlias() . '_envs',
            $config['envs']
        );

        $loader = new YamlFileLoader(
            $container,
            new FileLocator($configDir)
        );
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        $config = new Configuration($this->getAlias());
        $r = new \ReflectionClass($config);
        $container->addResource(new FileResource($r->getFileName()));
        return $config;
    }
}
