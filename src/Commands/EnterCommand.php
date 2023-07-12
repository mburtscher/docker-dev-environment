<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnterCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('enter');
        $this->setDescription('Enters the app container.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        passthru('docker exec -it '.$this->getProjectIdentifier().'-app-1 /bin/bash');
        return 0;
    }
}
