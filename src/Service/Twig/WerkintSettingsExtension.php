<?php
namespace Werkint\Bundle\SettingsBundle\Service\Twig;

use Diplom\Data\Entity\Category;
use Diplom\Data\Entity\WorkFile;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Werkint\Bundle\WebappBundle\Twig\AbstractExtension;

/**
 * WerkintSettingsExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSettingsExtension extends AbstractExtension
{
    const EXT_NAME = 'werkint_settings';

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->addFunction('settingToggler', true, function ($name, $class, $isactive, $labels = null) use (&$translator) {
            if (!$labels) {
                $labels = [
                    $translator->trans('turn.on', [], 'WerkintSettings'),
                    $translator->trans('turn.off', [], 'WerkintSettings'),
                ];
            }

            return '<a href="#toggle" class="action-toggle  ' . ($isactive ? 'active' : '') . '" data-toggle-name="' . $name . '" data-toggle-class="' . $class . '"><span class="active">' . $labels[0] . '</span><span class="unactive">' . $labels[1] . '</span></a>';
        });
    }
}
