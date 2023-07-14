<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Exception;
use Mburtscher\DockerDevEnvironment\ComposeCommand;
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
        try {
            $command = ComposeCommand::fromConfig($this->getConfig());
        } catch (Exception $ex) {
            $output->writeln('<error>'.$ex->getMessage().'</error>');
            return self::FAILURE;
        }

        $output->writeln('Stopping environment…');

        $process = new Process($command->getDownCommand());
        $process->setTimeout(null);
        $process->run(fn ($type, $buffer) => $output->write($buffer));

        return self::SUCCESS;
    }
}
