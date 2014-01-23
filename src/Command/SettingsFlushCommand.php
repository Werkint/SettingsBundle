<?php
namespace Werkint\Bundle\SettingsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SettingsFlushCommand.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SettingsFlushCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('werkint:settings:flush')
            ->setDescription('Flushes config');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $output->write('Compiling... ');
        $this->serviceCompiler()->compile();
        $output->writeln('done');
    }

    // -- Service ---------------------------------------

    protected function serviceCompiler()
    {
        return $this->getContainer()->get('werkint.settings.compiler');
    }
}
