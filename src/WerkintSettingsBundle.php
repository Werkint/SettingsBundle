<?php
namespace Werkint\Bundle\SettingsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * WerkintSettingsBundle.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSettingsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
