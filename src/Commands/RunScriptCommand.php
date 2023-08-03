<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__.'/../../vendor/autoload.php';

class RunScriptCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('run-script')
            ->setDescription('Runs a composer script within the app container.')
            ->addArgument('script', InputArgument::REQUIRED, 'The Composer script name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        passthru('docker exec -it '.$this->getStackName().'-app-1 composer '.escapeshellarg($input->getArgument('script')));
        return 0;
    }
}
