<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__.'/../../vendor/autoload.php';

class EnterCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('enter')
            ->setDescription('Enters a specific component container.')
            ->addArgument('component', InputArgument::OPTIONAL, 'The component to enter.', 'app');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        passthru('docker exec -it '.$this->getStackName().'-'.$input->getArgument('component').'-1 /bin/bash');
        return 0;
    }
}
