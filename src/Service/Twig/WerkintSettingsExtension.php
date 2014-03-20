<?php
namespace Werkint\Bundle\SettingsBundle\Service\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Werkint\Bundle\FrameworkExtraBundle\Twig\AbstractExtension;

/**
 * WerkintSettingsExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSettingsExtension extends AbstractExtension
{
    const EXT_NAME = 'werkint_settings';

    protected $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->addFunction('settingToggler', true, function (
            $name,
            $class,
            $isactive,
            $labels = null
        ) {
            if (!$labels) {
                $labels = [
                    $this->translator->trans('turn.on', [], 'WerkintSettings'),
                    $this->translator->trans('turn.off', [], 'WerkintSettings'),
                ];
            }

            return '<a href="#toggle" class="action-toggle  ' . ($isactive ? 'active' : '') . '" data-toggle-name="' . $name . '" data-toggle-class="' . $class . '"><span class="active">' . $labels[0] . '</span><span class="unactive">' . $labels[1] . '</span></a>';
        });
    }
}