<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Exception;
use Mburtscher\DockerDevEnvironment\ComponentCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

require_once __DIR__.'/../../vendor/autoload.php';

class StartCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('start')
            ->setDescription('Starts the Docker development environment.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $components = ComponentCollection::fromConfig($this->getConfig());
        } catch (Exception $ex) {
            $output->writeln('<error>'.$ex->getMessage().'</error>');
            return self::FAILURE;
        }

        $output->writeln('Starting environment…');

        $output->writeln(implode(' ', $this->buildUpCommand($components)));
        foreach ($components->getAllEnvironmentVariables() as $key => $value) {
            $output->writeln($key.': '.$value);
        }

        $process = new Process($this->buildUpCommand($components), null, $components->getAllEnvironmentVariables());
        $process->setTimeout(null);
        $process->run(fn ($type, $buffer) => $output->write($buffer));

        return self::SUCCESS;
    }

    private function buildUpCommand(ComponentCollection $components): array
    {
        $cmd = ['docker', 'compose', '-p', $this->getStackName()];

        foreach ($components->getAllComposeFiles() as $file) {
            $cmd[] = '-f';
            $cmd[] = $file;
        }

        $cmd[] = 'up';
        $cmd[] = '-d';

        return $cmd;
    }
}
