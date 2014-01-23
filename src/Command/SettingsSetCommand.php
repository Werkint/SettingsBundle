<?php
namespace Werkint\Bundle\SettingsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SettingsSetCommand.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SettingsSetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('werkint:settings:set')
            ->addArgument('name')
            ->addArgument('value')
            ->addArgument('env')
            ->setDescription('Sets config setting');
    }

    protected function setRowValue(
        OutputInterface $output,
        Setting $row,
        $val2,
        $prefix = ''
    ) {
        $val1 = $this->serviceSettingsRepo()->getSettingValue($row);

        if ($val1 == $val2) {
            $output->writeln($prefix . 'skip, value: ' . $val1);
        } else {
            $output->writeln($prefix . 'old value: ' . $val1);
            $output->writeln($prefix . 'new value: ' . $val2);
            $row->setValue($val2)
                ->setIsEncrypted(false);

            $this->serviceManager()->flush($row);
        }
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $name = $input->getArgument('name');
        $setting = $this->serviceSettingsRepo()->findSetting(
            $name,
            $input->getArgument('env')
        );
        if (!$setting) {
            throw new \Exception('Wrong setting name: ' . $name);
        }

        if ($setting->getType()->getClass() == 'array') {
            $output->writeln('Array ' . $name . ':');
            $data = json_decode($input->getArgument('value'), true);
            $row = $this->serviceSettingsRepo()->newSettingChild($setting, true);
            foreach ($row->getChildren() as $child) {
                /** @var Setting $child */
                if (isset($data[$child->getClass()])) {
                    $this->setRowValue(
                        $output,
                        $child,
                        $data[$child->getClass()],
                        '    ' . $child->getClass() . ' '
                    );
                }
            }
        } else {
            $output->writeln('Option ' . $name . ':');

            $this->setRowValue(
                $output,
                $setting,
                $input->getArgument('value'),
                '    '
            );
        }

        $command = $this->commandEncrypter();
        $command->run(new ArrayInput(['--encryptall' => true, '']), $output);
    }

    // -- Service ---------------------------------------

    /**
     * @return EncrypterCommand
     */
    protected function commandEncrypter()
    {
        return $this->getApplication()->find('werkint:encrypter');
    }

    protected function serviceSettingsRepo()
    {
        return $this->getContainer()->get('werkint.repo.settings');
    }

    protected function serviceManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
