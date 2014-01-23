<?php
namespace Werkint\Bundle\SettingsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
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

        $loader = new YamlFileLoader(
            $container,
            new FileLocator($configDir)
        );
        $loader->load('services.yml');
    }

}
