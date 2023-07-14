<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Exception;
use Mburtscher\DockerDevEnvironment\ComponentCollection;
use Mburtscher\DockerDevEnvironment\Config\ComposerJson;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

require_once __DIR__.'/../../vendor/autoload.php';

class StopCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('stop')
            ->setDescription('Stops the Docker development environment.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Stopping environment…');

        $process = new Process($this->buildDownCommand());
        $process->setTimeout(null);
        $process->run(fn ($type, $buffer) => $output->write($buffer));

        return self::SUCCESS;
    }

    private function buildDownCommand(): array
    {
        return ['docker', 'compose', '-p', $this->getStackName(), 'down'];
    }
}
